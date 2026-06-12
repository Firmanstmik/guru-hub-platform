<?php

namespace App\Http\Controllers;

use App\Models\CourseMaterial;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quizze;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentQuizController extends Controller
{
    /**
     * 1. Halaman Transisi sebelum mulai Kuis (Landing Page Kuis)
     */
    public function introduce($materialId)
    {
        // Ambil materi beserta kuisnya
        $material = CourseMaterial::with('quiz')->findOrFail($materialId);

        if (!$material->quiz) {
            return redirect()->back()->with('error', 'Kuis untuk materi ini belum diaktifkan oleh guru.');
        }

        $quiz = $material->quiz;
        $userId = auth()->id();

        // Cek apakah siswa sudah pernah mengerjakan kuis ini sebelumnya
        $alreadyTaken = StudentAnswer::where('user_id', $userId)
            ->whereHas('question', function ($q) use ($quiz) {
                $q->where('quiz_id', $quiz->id);
            })->exists();

        return view('student.quiz.introduce', compact('material', 'quiz', 'alreadyTaken'));
    }

    /**
     * 2. Lembar Kerja Kuis (Tempat Soal Di-render)
     */
    public function takeQuiz($quizId)
    {
        // Ambil kuis beserta soal-soal dan opsi pilihan gandanya secara acak (inRandomOrder)
        $quiz = Quizze::with(['material', 'questions' => function ($query) {
            $query->inRandomOrder()->with('options');
        }])->findOrFail($quizId);

        $userId = auth()->id();

        // Keamanan: Jika sudah pernah mengerjakan, tendang balik agar tidak bisa ujian ulang
        $alreadyTaken = StudentAnswer::where('user_id', $userId)
            ->whereHas('question', function ($q) use ($quizId) {
                $q->where('quiz_id', $quizId);
            })->exists();

        if ($alreadyTaken) {
            return redirect('/materials/' . $quiz->material_id . 'quiz')
                ->with('error', 'Anda sudah mengerjakan kuis ini sebelumnya.');
        }

        return view('student.quiz.take', compact('quiz'));
    }

    /**
     * Fungsi untuk memproses pengumpulan kuis oleh Siswa
     */
    public function submitQuiz(Request $request, $quizId)
    {
        // Validasi input jawaban dari form blade siswa
        $request->validate([
            'answers' => 'required|array',
        ]);

        $userId = auth()->id();
        $answers = $request->input('answers'); // Berisi array: [question_id => jawaban_siswa]

        DB::beginTransaction();
        try {
            foreach ($answers as $questionId => $answerValue) {
                // Ambil data pertanyaan untuk tahu bobot poin (points) dan jenis soal (type)
                $question = Question::findOrFail($questionId);

                // Set nilai default awal pengerjaan
                $isCorrect = null;
                $scoreAchieved = 0;
                $answerText = '';

                // KONDISI 1: JIKA SOAL PILIHAN GANDA (DI SINI KODE ANDA DITEMPATKAN)
                if ($question->isMultipleChoice()) {
                    $chosenOptionId = $answerValue; // Nilainya berupa ID dari tabel question_options
                    $answerText = $chosenOptionId;

                    // --- POTONGAN KODE ANDA DI SINI ---
                    // Cek otomatis ke database apakah ID opsi yang dipilih siswa bernilai true pada field is_correct
                    $isCorrect = QuestionOption::where('id', $chosenOptionId)
                        ->where('is_correct', true)
                        ->exists();

                    // Jika benar berikan poin penuh soal, jika salah beri 0
                    $scoreAchieved = $isCorrect ? $question->points : 0;
                    // --- AKHIR POTONGAN KODE ANDA ---
                }

                // KONDISI 2: JIKA ESSAY
                elseif ($question->isEssay()) {
                    $answerText = $answerValue; // Berupa teks ketikan bebas
                    $isCorrect = null;          // Tetap null agar guru tahu ini perlu diperiksa manual
                    $scoreAchieved = 0;         // Nilai awal sebelum dikoreksi guru
                }

                // KONDISI 3: JIKA PDF ATTACHMENT
                elseif ($question->isPdfAttachment()) {
                    // Proses upload file jawaban PDF siswa
                    if ($request->hasFile("answers.{$questionId}")) {
                        $file = $request->file("answers.{$questionId}");
                        $path = $file->store('quiz_answers_pdf', 'public');
                        $answerText = $path; // Simpan path file PDF
                    }
                    $isCorrect = null;
                    $scoreAchieved = 0;
                }

                // SIMPAN LANGSUNG KE TABEL student_answers
                StudentAnswer::create([
                    'user_id' => $userId,
                    'question_id' => $questionId,
                    'answer_text' => $answerText,
                    'is_correct' => $isCorrect,
                    'score_achieved' => $scoreAchieved
                ]);
            }

            DB::commit();
            return redirect('/siswa-dashboard')->with('success', 'Kuis Anda berhasil dikumpulkan dan jawaban pilihan ganda telah dinilai otomatis!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat mengirim kuis: ' . $e->getMessage());
        }
    }

    public function history()
    {
        $userId = auth()->id();

        // Mengambil semua kuis yang sudah pernah dikerjakan oleh siswa ini
        $quizHistory = Quizze::whereHas('questions.studentAnswers', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with(['material', 'questions.studentAnswers' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get()
            ->map(function ($quiz) use ($userId) { // <-- Di sini sudah benar menggunakan use ($userId)

                $totalScoreAchieved = 0;
                $maxScore = $quiz->questions->sum('points');
                $hasPendingReview = false;

                // Buat variabel bantuan untuk mencatat tanggal submit terakhir
                $latestSubmissionDate = null;

                foreach ($quiz->questions as $question) {
                    $answer = $question->studentAnswers->first(); // Jawaban siswa untuk soal ini

                    if ($answer) {
                        $totalScoreAchieved += $answer->score_achieved;

                        // Cek tanggal submit paling baru dari jawaban-jawaban yang ada
                        if (!$latestSubmissionDate || $answer->created_at > $latestSubmissionDate) {
                            $latestSubmissionDate = $answer->created_at;
                        }

                        // Jika ada tipe essay/pdf yang kolom is_correct-nya masih null, berarti belum dinilai guru
                        if (in_array($question->type, ['essay', 'pdf_attachment']) && is_null($answer->is_correct)) {
                            $hasPendingReview = true;
                        }
                    }
                }

                // Simpan data kalkulasi ke dalam objek objek secara dinamis agar bisa dibaca di Blade
                $quiz->student_score = $totalScoreAchieved;
                $quiz->max_score = $maxScore;
                $quiz->need_review = $hasPendingReview;

                // PERBAIKAN DI SINI: Langsung gunakan variabel bantuan tadi tanpa memanggil $userId lagi
                $quiz->submitted_at = $latestSubmissionDate;

                return $quiz;
            })
            ->sortByDesc('submitted_at'); // Urutkan dari yang paling baru dikerjakan

        return view('student.quiz.history', compact('quizHistory'));
    }
}

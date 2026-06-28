<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Quizze;
use App\Models\StudentAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class QuizManagementController extends Controller
{
    // =========================================================================
    // 1. MANAJEMEN KUIS (PROSES PEMBUATAN OLEH GURU)
    // =========================================================================
    public function buildQuiz($quizId)
    {
        // Ambil data kuis beserta relasi pertanyaan dan opsinya
        $quiz = Quizze::with(['material', 'questions.options'])->findOrFail($quizId);

        return view('guru.quiz.build', compact('quiz'));
    }

    /**
     * Menyimpan kuis baru yang dikaitkan pada suatu materi.
     */
    public function storeQuiz(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:course_materials,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        try {
            Quizze::create($request->all());
            return redirect()->back()->with('success', 'Kuis materi berhasil dibuat!');
        } catch (Exception $e) {
            Log::error('Gagal membuat kuis: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat kuis baru.');
        }
    }

    /**
     * Menyimpan pertanyaan baru ke dalam kuis (Multi-type: PG, Essay, PDF).
     */
    public function storeQuestion(Request $request, $quizId)
    {
        $request->validate([
            'question_text' => 'required_unless:type,pdf_attachment|string|nullable',
            'type' => 'required|in:multiple_choice,essay,pdf_attachment',
            'points' => 'required|integer|min:1',
            'pdf_file' => 'required_if:type,pdf_attachment|file|mimes:pdf|max:5120',

            // PERBAIKAN VALIDASI DI SINI:
            'options' => 'required_if:type,multiple_choice|array',
            'options.*' => 'nullable|string', // Berikan 'nullable' agar tidak memprotes input kosong saat tipe essay
            'correct_option' => 'required_if:type,multiple_choice',
        ]);

        DB::beginTransaction();
        try {
            $quiz = Quizze::findOrFail($quizId);

            // Data dasar pembuatan soal
            $questionData = [
                'quiz_id' => $quiz->id,
                'question_text' => $request->question_text,
                'type' => $request->type,
                'points' => $request->points,
            ];

            // Jika tipe soal adalah lampiran PDF (misal lembar studi kasus dari guru)
            if ($request->type === 'pdf_attachment' && $request->hasFile('pdf_file')) {
                $path = $request->file('pdf_file')->store('quiz_questions_pdf', 'public');
                $questionData['pdf_file_path'] = $path;

                if (empty($request->question_text)) {
                    $questionData['question_text'] = 'Silahkan kerjakan soal yang terlampir pada file PDF berikut.';
                }
            }

            // Simpan pertanyaan
            $question = Question::create($questionData);

            // Jika tipe soal pilihan ganda, simpan opsi-opsinya
            if ($request->type === 'multiple_choice') {
                foreach ($request->options as $index => $optionText) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct' => ($request->correct_option == $index), // Menandai kunci jawaban yang benar
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pertanyaan baru berhasil ditambahkan ke dalam kuis!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan pertanyaan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pertanyaan.');
        }
    }


    // =========================================================================
    // 2. PENILAIAN JAWABAN SISWA (REVIEW OLEH GURU)
    // =========================================================================

    /**
     * Menampilkan daftar siswa yang telah mengerjakan kuis pada materi ini.
     */
    public function reviewStudents($quizId)
    {
        $quiz = Quizze::with('material')->findOrFail($quizId);

        // Mengambil daftar siswa unik yang sudah mengumpulkan jawaban di kuis ini
        $submissions = User::whereHas('studentAnswers.question', function ($q) use ($quizId) {
            $q->where('quiz_id', $quizId);
        })
            ->with(['studentAnswers' => function ($q) use ($quizId) {
                $q->whereHas('question', function ($query) use ($quizId) {
                    $query->where('quiz_id', $quizId);
                });
            }])
            ->get()
            ->map(function ($student) use ($quiz) {
                // Kalkulasi skor total sementara yang didapatkan siswa
                $student->total_score = $student->studentAnswers->sum('score_achieved');
                // Cek apakah ada esai/pdf yang belum dinilai (is_correct masih null)
                $student->need_review = $student->studentAnswers->whereNull('is_correct')->count() > 0;
                return $student;
            });

        return view('guru.quiz.submission', compact('quiz', 'submissions'));
    }

    /**
     * Membuka lembar jawaban spesifik milik satu siswa untuk dikoreksi.
     */
    public function reviewSingleStudent($quizId, $studentId)
    {
        $quiz = Quizze::findOrFail($quizId);
        $student = User::findOrFail($studentId);

        // Ambil semua pertanyaan kuis digabung dengan jawaban riil dari siswa tersebut
        $answers = StudentAnswer::with(['question.options', 'chosenOption'])
            ->where('user_id', $studentId)
            ->whereHas('question', function ($q) use ($quizId) {
                $q->where('quiz_id', $quizId);
            })->get();

        return view('guru.quiz.review-grade', compact('quiz', 'student', 'answers'));
    }

    /**
     * Menyimpan nilai esai / PDF yang diinput secara manual oleh guru.
     */
    public function submitGrade(Request $request, $answerId)
    {
        $request->validate([
            'score' => 'required|integer|min:0',
            'is_correct' => 'required|boolean' // Guru menentukan jawaban ini dikategori benar atau salah
        ]);

        try {
            $answer = StudentAnswer::with('question')->findOrFail($answerId);

            // Batasi agar nilai inputan guru tidak melebihi bobot maksimal pertanyaan tersebut
            if ($request->score > $answer->question->points) {
                return redirect()->back()->with('error', 'Nilai input melebihi bobot maksimal poin soal yaitu: ' . $answer->question->points);
            }

            // Update nilai kelulusan indikator soal dan skor pencapaian
            $answer->update([
                'is_correct' => $request->is_correct,
                'score_achieved' => $request->score
            ]);

            return redirect()->back()->with('success', 'Skor berhasil diperbarui!');
        } catch (Exception $e) {
            Log::error('Gagal menyimpan nilai koreksi guru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses penilaian.');
        }
    }
}

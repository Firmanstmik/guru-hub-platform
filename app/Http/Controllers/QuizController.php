<?php

namespace App\Http\Controllers;

use App\Models\Question as Question;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function submitQuiz(Request $request, $quizId)
    {
        $user = auth()->user();
        $answers = $request->input('answers'); // Menangkap array jawaban

        foreach ($answers as $questionId => $answerValue) {
            $question = Question::find($questionId);

            // Buat instance jawaban baru
            $studentAnswer = new StudentAnswer();
            $studentAnswer->user_id = $user->id;
            $studentAnswer->question_id = $questionId;

            if ($question->type === 'pdf_attachment') {
                // Jika Tipenya PDF, ambil file dari request dan upload
                if ($request->hasFile("answers.{$questionId}")) {
                    $file = $request->file("answers.{$questionId}");
                    $path = $file->store('quiz_answers_pdf', 'public');

                    $studentAnswer->answer_text = $path; // Yang disimpan adalah path file-nya
                }
            } else {
                // Jika Tipenya Pilihan Ganda (ID Opsi) atau Essay (Teks bebas)
                $studentAnswer->answer_text = $answerValue;
            }

            $studentAnswer->save();
        }

        return redirect('/siswa-dashboard')->with('success', 'Kuis berhasil dikumpulkan!');
    }
}

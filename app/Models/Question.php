<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'type',
        'pdf_file_path',
        'points'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quizze::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function isMultipleChoice(): bool
    {
        return $this->type === 'multiple_choice';
    }

    public function isEssay(): bool
    {
        return $this->type === 'essay';
    }

    public function isPdfAttachment(): bool
    {
        return $this->type === 'pdf_attachment';
    }
}

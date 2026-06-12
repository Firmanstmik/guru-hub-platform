<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question_id',
        'answer_text',
        'is_correct',
        'score_achieved'
    ];
    
    protected $casts = [
        'is_correct' => 'boolean',
        'score_achieved' => 'integer'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function chosenOption()
    {
        return $this->belongsTo(QuestionOption::class, 'answer_text');
    }
}

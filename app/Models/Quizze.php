<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizze extends Model
{
    protected $fillable = ['material_id', 'title', 'description', 'duration_minutes'];

    public function material()
    {
        return $this->belongsTo(CourseMaterial::class, 'material_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id')->with('options');
    }
}

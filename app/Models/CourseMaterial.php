<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseMaterial extends Model
{
    protected $fillable = ['course_id', 'title', 'file_path'];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function quiz()
    {
        return $this->hasOne(Quizze::class, 'material_id');
    }
}

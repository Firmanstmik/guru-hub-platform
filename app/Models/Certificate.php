<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['student_id', 'course_id', 'certificate_code', 'file_path', 'issued_at'];
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    protected $casts = [
        'issued_at' => 'date',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentBiodata extends Model
{
    protected $table = 'student_biodatas';

    protected $fillable = [
        'user_id',
        'nisn',
        'institution_name',
        'education_level_id',
        'birth_date',
        'gender',
        'address',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }
}

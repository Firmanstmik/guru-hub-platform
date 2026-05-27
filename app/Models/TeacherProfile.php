<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherProfile extends Model
{
    protected $fillable = [
        'user_id', 'title', 'bio', 'skills_tags', 'verification_status', 
        'cv_file', 'average_rating', 'bank_name', 'bank_account_number', 'bank_account_name'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'student_id', 
        'course_id', 
        'invoice_number', 
        'amount', 
        'payment_proof_path', 
        'status', 
        'earning_status',
        'verified_at', 
        'verified_by', 
        'rejection_reason'
        ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function course() { return $this->belongsTo(Course::class); }
    public function verifier() { return $this->belongsTo(User::class, 'verified_by'); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherEarning extends Model
{
    protected $fillable = ['teacher_id', 'payment_id', 'amount_earned', 'status'];
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function payment() { return $this->belongsTo(Payment::class); }
}

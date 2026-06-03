<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'student_id',
        'course_id',
        'total_amount',
        'note',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function schedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class, 'course_id', 'course_id')
            ->latestOfMany();
    }
}

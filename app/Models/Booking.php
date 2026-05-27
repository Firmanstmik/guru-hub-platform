<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['student_id', 'schedule_id', 'status'];
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function schedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'schedule_id');
    }
}

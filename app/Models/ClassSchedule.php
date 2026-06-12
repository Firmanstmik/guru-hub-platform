<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClassSchedule extends Model
{
    protected $fillable = [
        'course_id',
        'material_id',
        'topic',
        'start_time',
        'end_time',
        'platform',
        'meeting_link',
        'meeting_id',
        'meeting_password'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'schedule_id');
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'avatar',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function studentBiodata(): HasOne
    {
        return $this->hasOne(StudentBiodata::class, 'user_id');
    }

    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class, 'user_id');
    }

    public function teacherCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_students', 'student_id', 'course_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'student_id');
    }

    public function reviews(): HasMany
    {
        $foreignKey = $this->hasRole('teacher') ? 'teacher_id' : 'student_id';

        return $this->hasMany(Review::class, $foreignKey);
    }

    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'user_id');
    }

    public function hasCustomAvatar(): bool
    {
        if (blank($this->avatar)) {
            return false;
        }

        if (in_array($this->avatar, ['default-avatar.png', 'default-guru.png', 'default-siswa.png'], true)) {
            return false;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->exists($this->avatar);
    }

    public function avatarUrl(): string
    {
        if ($this->hasCustomAvatar()) {
            return asset('storage/' . $this->avatar);
        }

        if ($this->hasRole('guru')) {
            return asset('assets/avatar/default-guru.png');
        }

        if ($this->hasRole('siswa')) {
            return asset('assets/avatar/default-siswa.png');
        }

        return asset('assets/avatar/default-avatar.png');
    }
}

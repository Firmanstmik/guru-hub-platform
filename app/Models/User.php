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
        'name', 'email', 'password', 'phone_number', 'avatar', 'is_active'
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

    protected static function booted()
    {
        static::created(function ($user) {
            $user->assignRole('siswa');
        });
    }

    // Hubungan ke profil guru (jika role = teacher)
    public function teacherProfile(): HasOne
    {
        return $this->hasOne(TeacherProfile::class, 'user_id');
    }

    // Kelas yang dibuat oleh Guru
    public function teacherCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Kelas yang diikuti oleh Siswa (Many-to-Many via course_students)
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_students', 'student_id', 'course_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    // Booking yang dilakukan oleh Siswa
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'student_id');
    }

    // Ulasan yang diberikan oleh Siswa atau diterima oleh Guru
    public function reviews(): HasMany
    {
        // Menggunakan method hasRole() dari Spatie untuk menentukan foreign key
        $foreignKey = $this->hasRole('teacher') ? 'teacher_id' : 'student_id';
        
        return $this->hasMany(Review::class, $foreignKey);
    }
}

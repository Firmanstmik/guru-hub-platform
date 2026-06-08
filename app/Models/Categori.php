<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Categori extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'slug', 'description'];

    // Mendapatkan semua kelas dalam kategori ini
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'category_id');
    }

    public function teachers(): HasManyThrough
    {
        // Parameter: (ModelTujuan, ModelPerantara, FK_di_ModelPerantara, FK_di_ModelTujuan, PK_di_ModelAsal, PK_di_ModelPerantara)
        return $this->hasManyThrough(
            \App\Models\User::class, // Sesuaikan namespace model User/Guru Anda
            Course::class,
            'category_id', // Foreign key di tabel courses
            'id',          // Foreign key di tabel users (primary key guru)
            'id',          // Local key di tabel categories
            'teacher_id'   // Local key di tabel courses
        );
    }
}

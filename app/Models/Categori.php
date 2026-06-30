<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Categori extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'slug', 'description', 'icon', 'sort_order', 'is_active', 'is_featured'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
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

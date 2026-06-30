<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageTestimonial extends Model
{
    protected $fillable = [
        'name',
        'role_title',
        'quote',
        'rating',
        'gradient_from',
        'gradient_to',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'rating' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('id');
    }
}

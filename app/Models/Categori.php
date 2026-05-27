<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categori extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'slug', 'description'];

    // Mendapatkan semua kelas dalam kategori ini
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}

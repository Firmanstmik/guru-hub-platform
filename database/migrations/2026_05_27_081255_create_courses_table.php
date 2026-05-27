<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete(); // Mencegah kategori terhapus jika ada kelas didalamnya
            $table->string('title'); // Contoh: "Dasar Percakapan Kaiwa N5" atau "Kelas Vokal Pemula"
            $table->text('description');
            $table->string('cover_image')->nullable();
            $table->decimal('price', 10, 2)->default(0); // Harga paket kelas
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

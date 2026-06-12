<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('progressable_id');
            $table->string('progressable_type');
            
            $table->timestamps();
            $table->unique(['user_id', 'progressable_id', 'progressable_type'], 'user_progress_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role_title');
            $table->text('quote');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->string('gradient_from', 20)->default('#14B8A6');
            $table->string('gradient_to', 20)->default('#0E7490');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_testimonials');
    }
};

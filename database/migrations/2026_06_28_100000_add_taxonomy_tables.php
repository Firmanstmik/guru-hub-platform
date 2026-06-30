<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon', 32)->nullable()->after('slug');
            $table->unsignedSmallInteger('sort_order')->default(0)->after('icon');
            $table->boolean('is_active')->default(true)->after('sort_order');
            $table->boolean('is_featured')->default(false)->after('is_active');
        });

        Schema::create('education_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon', 32)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('education_level_id')->constrained('education_levels')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['category_id', 'education_level_id', 'slug']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('subject_id')->nullable()->after('category_id')->constrained('subjects')->nullOnDelete();
            $table->foreignId('education_level_id')->nullable()->after('subject_id')->constrained('education_levels')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('subject_id');
            $table->dropConstrainedForeignId('education_level_id');
        });

        Schema::dropIfExists('subjects');
        Schema::dropIfExists('education_levels');

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['icon', 'sort_order', 'is_active', 'is_featured']);
        });
    }
};

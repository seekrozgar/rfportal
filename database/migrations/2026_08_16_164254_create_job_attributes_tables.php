<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ✅ Job Categories
        Schema::create('job_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('job_categories')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Job Types
        Schema::create('job_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Job Shifts
        Schema::create('job_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Experience Levels
        Schema::create('experience_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('years_min')->nullable();
            $table->integer('years_max')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Industries
        Schema::create('industries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Functional Areas
        Schema::create('functional_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('industry_id')->nullable()->constrained('industries')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Skills
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Language Levels
        Schema::create('language_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Career Levels
        Schema::create('career_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('level_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Degree Levels
        Schema::create('degree_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Degree Types
        Schema::create('degree_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Major Subjects
        Schema::create('major_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('degree_level_id')->nullable()->constrained('degree_levels')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Result Types
        Schema::create('result_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Marital Statuses
        Schema::create('marital_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Ownership Types
        Schema::create('ownership_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Salary Periods
        Schema::create('salary_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ✅ Genders
        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genders');
        Schema::dropIfExists('salary_periods');
        Schema::dropIfExists('ownership_types');
        Schema::dropIfExists('marital_statuses');
        Schema::dropIfExists('result_types');
        Schema::dropIfExists('major_subjects');
        Schema::dropIfExists('degree_types');
        Schema::dropIfExists('degree_levels');
        Schema::dropIfExists('career_levels');
        Schema::dropIfExists('language_levels');
        Schema::dropIfExists('skills');
        Schema::dropIfExists('functional_areas');
        Schema::dropIfExists('industries');
        Schema::dropIfExists('experience_levels');
        Schema::dropIfExists('job_shifts');
        Schema::dropIfExists('job_types');
        Schema::dropIfExists('job_categories');
    }
};

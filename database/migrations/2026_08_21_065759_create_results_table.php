<?php
// database/migrations/2026_08_21_000002_create_results_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('file_path')->nullable(); // PDF or image file
            $table->string('file_original_name')->nullable();
            $table->string('institution')->nullable();
            $table->string('exam_type')->nullable(); // CSS, PPSC, FPSC, etc.
            $table->date('result_date')->nullable();
            $table->string('category')->nullable(); // Jobs, Admissions, Scholarships
            $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_published')->default(true);
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};

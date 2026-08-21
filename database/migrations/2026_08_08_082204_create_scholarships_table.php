<?php
// database/migrations/2026_08_22_000001_create_scholarships_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('provider')->nullable();
            $table->string('country')->nullable();
            $table->string('university')->nullable();
            $table->string('degree_level')->nullable(); // Bachelor, Master, PhD, etc.
            $table->string('scholarship_type')->nullable(); // Fully Funded, Partial, Tuition Waiver
            $table->string('amount')->nullable(); // Amount in PKR/USD
            $table->date('deadline')->nullable();
            $table->string('apply_link')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('featured_image_original')->nullable();
            $table->text('eligibility')->nullable();
            $table->text('benefits')->nullable();
            $table->text('required_documents')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('source_url')->nullable(); // Original source URL
            $table->string('source')->nullable(); // propakistani, official, etc.
            $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_published')->default(true);
            $table->boolean('is_draft')->default(false);
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};

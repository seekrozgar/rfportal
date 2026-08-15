<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();

            // ✅ Foreign Keys
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');

            // ✅ Basic Info
            $table->string('title');
            $table->string('slug')->unique()->index();

            // ✅ Description
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();

            // ✅ Location
            $table->string('location');

            // ✅ Salary
            $table->string('salary_min')->nullable();
            $table->string('salary_max')->nullable();
            $table->string('salary_period')->nullable(); // monthly, yearly, hourly

            // ✅ Job Details
            $table->string('job_type'); // full-time, part-time, contract, freelance
            $table->string('experience_level')->nullable();
            $table->date('application_deadline');

            // ✅ Source & Status
            $table->enum('source', ['admin', 'employer', 'scraped'])->default('employer');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            // ✅ Stats
            $table->integer('views_count')->default(0);

            // ✅ Timestamps
            $table->timestamps();

            // ✅ Indexes for performance
            $table->index(['company_id', 'is_active']);
            $table->index(['category_id', 'is_active']);
            $table->index(['is_active', 'application_deadline']);
            $table->index(['is_featured', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};

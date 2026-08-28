<?php
// database/migrations/xxxx_xx_xx_create_job_applications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

            // ✅ Application Details
            $table->foreignId('job_id')->constrained('job_postings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('application_reference')->unique();

            // ✅ Application Status
            $table->enum('status', [
                'pending',
                'reviewing',
                'shortlisted',
                'interview',
                'offered',
                'hired',
                'rejected'
            ])->default('pending');

            // ✅ Application Data
            $table->text('cover_letter')->nullable();
            $table->string('resume')->nullable();
            $table->decimal('expected_salary', 15, 2)->nullable();
            $table->string('expected_salary_period')->nullable(); // monthly, annual
            $table->date('available_from')->nullable();
            $table->text('additional_info')->nullable();

            // ✅ Application Tracking
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('shortlisted_at')->nullable();
            $table->timestamp('interview_at')->nullable();
            $table->timestamp('offered_at')->nullable();
            $table->timestamp('hired_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // ✅ Notes & Feedback
            $table->text('employer_notes')->nullable();
            $table->text('feedback')->nullable();
            $table->integer('rating')->nullable(); // 1-5

            // ✅ Source
            $table->string('source')->nullable(); // website, linkedin, indeed, etc.
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            // ✅ Meta
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_archived')->default(false);

            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            // ✅ Indexes
            $table->index('status');
            $table->index('submitted_at');
            $table->index('is_read');
            $table->index('application_reference');
            $table->index(['job_id', 'status']);
            $table->index(['user_id', 'job_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_applications');
    }
};

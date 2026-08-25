<?php
// database/migrations/xxxx_xx_xx_create_job_postings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();

            // ✅ Basic Information
            $table->string('title');
            $table->string('slug')->unique();

            // ✅ Job Source (Auto-set based on logged-in user)
            $table->enum('job_source', ['admin', 'company'])->default('admin');
            $table->foreignId('company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('posted_by')->constrained('users');

            // ✅ Job Details - All Attribute Tables
            $table->foreignId('category_id')->nullable()->constrained('job_categories')->onDelete('set null');
            $table->foreignId('job_type_id')->nullable()->constrained('job_types')->onDelete('set null');
            $table->foreignId('job_shift_id')->nullable()->constrained('job_shifts')->onDelete('set null');
            $table->string('location')->nullable();
            $table->foreignId('experience_level_id')->nullable()->constrained('experience_levels')->onDelete('set null');
            $table->foreignId('career_level_id')->nullable()->constrained('career_levels')->onDelete('set null');
            $table->foreignId('industry_id')->nullable()->constrained('industries')->onDelete('set null');
            $table->foreignId('functional_area_id')->nullable()->constrained('functional_areas')->onDelete('set null');
            $table->foreignId('degree_level_id')->nullable()->constrained('degree_levels')->onDelete('set null');
            $table->foreignId('degree_type_id')->nullable()->constrained('degree_types')->onDelete('set null');
            $table->foreignId('major_subject_id')->nullable()->constrained('major_subjects')->onDelete('set null');
            $table->foreignId('gender_id')->nullable()->constrained('genders')->onDelete('set null');
            $table->foreignId('marital_status_id')->nullable()->constrained('marital_statuses')->onDelete('set null');
            $table->foreignId('language_level_id')->nullable()->constrained('language_levels')->onDelete('set null');

            // ✅ Salary Information
            $table->decimal('salary_min', 15, 2)->nullable();
            $table->decimal('salary_max', 15, 2)->nullable();
            $table->foreignId('salary_period_id')->nullable()->constrained('salary_periods')->onDelete('set null');

            // ✅ Advertisement
            $table->string('advertisement_image')->nullable();
            $table->string('apply_link')->nullable();
            $table->longText('description')->nullable();

            // ✅ Requirements & Benefits
            $table->longText('requirements')->nullable();
            $table->longText('benefits')->nullable();
            $table->text('skills_required')->nullable();
            $table->text('responsibilities')->nullable();

            // ✅ Application Details (Removed apply_email, apply_phone)
            $table->text('application_instructions')->nullable();

            // ✅ Deadline & Status
            $table->date('deadline')->nullable();
            $table->integer('vacancies')->default(1);
            $table->date('publish_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_remote')->default(false);
            $table->boolean('is_fresh')->default(false);
            $table->boolean('is_verified')->default(true);

            // ✅ Statistics
            $table->integer('views_count')->default(0);
            $table->integer('applications_count')->default(0);
            $table->integer('shares_count')->default(0);

            // ✅ Source & Meta
            $table->string('source')->nullable();
            $table->string('source_url')->nullable();
            $table->string('source_id')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // ✅ Publishing
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            // ✅ Indexes
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('is_verified');
            $table->index('job_source');
            $table->index('deadline');
            $table->index('location');
            $table->index('category_id');
            $table->index('company_id');
            $table->index('posted_by');
            $table->index(['is_active', 'deadline']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_postings');
    }
};

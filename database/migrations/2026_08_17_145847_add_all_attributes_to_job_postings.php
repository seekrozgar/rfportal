<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            // ✅ Replace salary_period with salary_period_id
            if (Schema::hasColumn('job_postings', 'salary_period')) {
                $table->dropColumn('salary_period');
            }
            if (!Schema::hasColumn('job_postings', 'salary_period_id')) {
                $table->foreignId('salary_period_id')->nullable()->constrained('salary_periods')->onDelete('cascade');
            }

            // ✅ New attribute columns
            if (!Schema::hasColumn('job_postings', 'job_shift_id')) {
                $table->foreignId('job_shift_id')->nullable()->constrained('job_shifts')->onDelete('cascade');
            }
            if (!Schema::hasColumn('job_postings', 'career_level_id')) {
                $table->foreignId('career_level_id')->nullable()->constrained('career_levels')->onDelete('cascade');
            }
            if (!Schema::hasColumn('job_postings', 'degree_level_id')) {
                $table->foreignId('degree_level_id')->nullable()->constrained('degree_levels')->onDelete('cascade');
            }
            if (!Schema::hasColumn('job_postings', 'gender_id')) {
                $table->foreignId('gender_id')->nullable()->constrained('genders')->onDelete('cascade');
            }
            if (!Schema::hasColumn('job_postings', 'industry_id')) {
                $table->foreignId('industry_id')->nullable()->constrained('industries')->onDelete('cascade');
            }
            if (!Schema::hasColumn('job_postings', 'functional_area_id')) {
                $table->foreignId('functional_area_id')->nullable()->constrained('functional_areas')->onDelete('cascade');
            }
            if (!Schema::hasColumn('job_postings', 'marital_status_id')) {
                $table->foreignId('marital_status_id')->nullable()->constrained('marital_statuses')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropForeign(['salary_period_id']);
            $table->dropColumn('salary_period_id');
            $table->dropForeign(['job_shift_id']);
            $table->dropColumn('job_shift_id');
            $table->dropForeign(['career_level_id']);
            $table->dropColumn('career_level_id');
            $table->dropForeign(['degree_level_id']);
            $table->dropColumn('degree_level_id');
            $table->dropForeign(['gender_id']);
            $table->dropColumn('gender_id');
            $table->dropForeign(['industry_id']);
            $table->dropColumn('industry_id');
            $table->dropForeign(['functional_area_id']);
            $table->dropColumn('functional_area_id');
            $table->dropForeign(['marital_status_id']);
            $table->dropColumn('marital_status_id');

            if (!Schema::hasColumn('job_postings', 'salary_period')) {
                $table->string('salary_period')->nullable();
            }
        });
    }
};

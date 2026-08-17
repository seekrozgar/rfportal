<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            // ✅ Advertisement Image
            if (!Schema::hasColumn('job_postings', 'ad_image')) {
                $table->string('ad_image')->nullable()->after('slug');
            }

            // ✅ Direct Apply Link
            if (!Schema::hasColumn('job_postings', 'apply_link')) {
                $table->string('apply_link')->nullable()->after('ad_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn(['ad_image', 'apply_link']);
        });
    }
};

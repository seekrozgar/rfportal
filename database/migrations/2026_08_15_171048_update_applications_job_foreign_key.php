<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // ✅ Drop old foreign key
            $table->dropForeign(['job_id']);
        });

        Schema::table('applications', function (Blueprint $table) {
            // ✅ Add new foreign key referencing job_postings
            $table->foreign('job_id')->references('id')->on('job_postings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });
    }
};

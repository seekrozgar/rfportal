<?php
// database/migrations/xxxx_xx_xx_create_companies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            // ✅ Basic Information
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();

            // ✅ Company Details
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('industry')->nullable();
            $table->string('company_size')->nullable(); // 1-10, 11-50, 51-200, 201-500, 500+
            $table->string('founded_year')->nullable();
            $table->string('headquarters')->nullable();

            // ✅ Verification
            $table->string('ntn_number')->nullable(); // National Tax Number
            $table->string('secp_number')->nullable(); // SECP Registration
            $table->string('business_license')->nullable(); // Business license file
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');

            // ✅ Social Links
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instagram')->nullable();
            $table->string('youtube')->nullable();

            // ✅ Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('job_posts_count')->default(0);

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('is_active');
            $table->index('is_verified');
            $table->index('slug');
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Schema::dropIfExists('companies');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};

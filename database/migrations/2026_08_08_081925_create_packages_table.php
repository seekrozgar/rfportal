<?php
// database/migrations/2026_08_20_000001_create_packages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->enum(['employer', 'seeker']); // employer | seeker
            $table->decimal('price', 10, 2);
            $table->integer('duration_days')->default(30);
            $table->json('features')->nullable(); // JSON features list
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('job_posts_limit')->nullable(); // For employer packages
            $table->integer('resume_views_limit')->nullable(); // For seeker packages
            $table->integer('application_boost')->default(0); // Boost percentage
            $table->string('badge_color')->default('#6c757d');
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};

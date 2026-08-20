<?php
// database/migrations/2026_08_20_000002_create_subscriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['employer', 'seeker']);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', ['active', 'expired', 'cancelled', 'pending'])->default('pending');
            $table->integer('job_posts_used')->default(0);
            $table->integer('job_posts_limit')->default(0);
            $table->integer('resume_views_used')->default(0);
            $table->integer('resume_views_limit')->default(0);
            $table->json('features_used')->nullable();
            $table->foreignId('payment_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('subscriptions');
        Schema::enableForeignKeyConstraints();
    }
};

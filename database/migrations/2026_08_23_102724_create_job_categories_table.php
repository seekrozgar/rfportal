<?php
// database/migrations/xxxx_xx_xx_create_job_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('job_categories');
        Schema::enableForeignKeyConstraints();

        Schema::create('job_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('job_categories')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->string('featured_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('keywords')->nullable();
            $table->timestamps();

            // ✅ Indexes
            $table->index('slug');
            $table->index('parent_id');
            $table->index('is_active');
            $table->index('order');
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('job_categories');
        Schema::enableForeignKeyConstraints();
    }
};

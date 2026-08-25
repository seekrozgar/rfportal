<?php
// database/migrations/xxxx_xx_xx_create_faqs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();

            // ✅ FAQ Fields
            $table->string('question');
            $table->longText('answer');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained('faq_categories')->onDelete('set null');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('helpful_count')->default(0);
            $table->integer('not_helpful_count')->default(0);

            // ✅ Meta Data
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();

            // ✅ Author
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->timestamps();

            // ✅ Indexes
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('order');
            $table->index('category_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('faqs');
    }
};

<?php
// database/migrations/2026_08_21_000003_create_admissions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admissions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description'); // ✅ CKEditor
            $table->string('institution');
            $table->text('programs_offered')->nullable(); // ✅ Sirf yeh rakhna hai
            $table->string('category')->nullable();
            $table->date('last_date')->nullable();
            $table->date('announcement_date')->nullable();
            $table->string('fee')->nullable();
            $table->string('apply_through')->nullable();
            $table->string('apply_link')->nullable();
            $table->longText('eligibility')->nullable(); // ✅ CKEditor
            $table->longText('required_documents')->nullable(); // ✅ CKEditor
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('featured_image_original')->nullable();
            $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_published')->default(true);
            $table->integer('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};

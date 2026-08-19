<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ Drop existing table if it has wrong structure
        Schema::dropIfExists('translations');

        // ✅ Create with correct structure
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index();
            $table->string('group')->nullable();
            $table->text('value');
            $table->string('language_code', 10);
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->timestamps();

            $table->unique(['key', 'language_code', 'group']);
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // en, ur, ar, etc.
            $table->string('name');
            $table->string('native_name')->nullable();
            $table->string('flag')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // ✅ Create translations table
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->index();
            $table->string('group')->nullable();
            $table->text('text');
            $table->string('language_code')->constrained('languages', 'code');
            $table->timestamps();

            $table->unique(['key', 'language_code', 'group']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('translations');
        Schema::dropIfExists('languages');
    }
};

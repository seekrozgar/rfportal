<?php
// database/migrations/xxxx_xx_xx_add_flag_class_to_languages.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->string('flag_class')->nullable()->after('flag');
            $table->string('direction')->default('ltr')->after('order');
        });
    }

    public function down()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->dropColumn(['flag_class', 'direction']);
        });
    }
};

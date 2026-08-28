<?php
// database/migrations/xxxx_xx_xx_add_company_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // ✅ Company fields for employers
            $table->boolean('is_company_profile_complete')->default(false);
            $table->timestamp('company_profile_completed_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_company_profile_complete', 'company_profile_completed_at']);
        });
    }
};

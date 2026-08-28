<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {


            $table->boolean('is_suspended')
                ->default(false)
                ->after('is_verified');

            $table->boolean('is_blocked')
                ->default(false)
                ->after('is_suspended');

            $table->boolean('is_fraud')
                ->default(false)
                ->after('is_blocked');

            $table->text('fraud_reason')
                ->nullable()
                ->after('is_fraud');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->dropColumn([

                'is_suspended',
                'is_blocked',
                'is_fraud',
                'fraud_reason',
            ]);
        });
    }
};

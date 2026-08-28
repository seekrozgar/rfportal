<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {

            $table->string('verification_status')
                ->default('not_requested')
                ->after('is_verified');

            $table->timestamp('verification_requested_at')
                ->nullable()
                ->after('verification_status');

            $table->timestamp('verification_reviewed_at')
                ->nullable()
                ->after('verification_requested_at');

            $table->foreignId('verification_reviewed_by')
                ->nullable()
                ->after('verification_reviewed_at')
                ->constrained('users')
                ->nullOnDelete();

            $table->text('verification_rejection_reason')
                ->nullable()
                ->after('verification_reviewed_by');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['verification_reviewed_by']);

            $table->dropColumn([
                'verification_status',
                'verification_requested_at',
                'verification_reviewed_at',
                'verification_reviewed_by',
                'verification_rejection_reason',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_audit_logs', function (Blueprint $table) {
            $table->id();

            // ✅ Company Reference
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Admin who performed action

            // ✅ Action Details
            $table->string('action'); // approve, reject, suspend, block, restore, mark_fraud, remove_fraud, unverify
            $table->string('status_before')->nullable();
            $table->string('status_after')->nullable();

            // ✅ Reasons & Notes
            $table->text('reason')->nullable(); // User-facing reason
            $table->text('admin_note')->nullable(); // Internal admin note
            $table->string('ticket_number')->nullable(); // Ticket/Reference number

            // ✅ Additional Metadata
            $table->json('metadata')->nullable(); // Extra data
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            // ✅ Indexes for faster queries
            $table->index(['company_id', 'action']);
            $table->index(['user_id', 'created_at']);
            $table->index('ticket_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_audit_logs');
    }
};

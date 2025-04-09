<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->index('candidate_id');
            $table->index('approver_id');
            $table->index('status');
            $table->index(['approver_id', 'status']);
            $table->index(['candidate_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approvals', function (Blueprint $table) {
            $table->dropIndex(['candidate_id']);
            $table->dropIndex(['approver_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['approver_id', 'status']);
            $table->dropIndex(['candidate_id', 'status']);
        });
    }
};

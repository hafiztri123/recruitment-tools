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
        Schema::table('candidate_stages', function (Blueprint $table) {
            // Add index on stage_id for faster lookups by stage
            $table->index('stage_id');
            $table->index('status');
            $table->index('scheduled_at');
            $table->index('completed_at');
            $table->index('passed');
            $table->index(['stage_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_stages', function (Blueprint $table) {
            $table->dropIndex(['stage_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['scheduled_at']);
            $table->dropIndex(['completed_at']);
            $table->dropIndex(['passed']);
            $table->dropIndex(['stage_id', 'status']);
        });
    }
};

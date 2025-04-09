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
        Schema::table('interviews', function (Blueprint $table) {
            $table->index('candidate_stage_id');
            $table->index('created_by');
            $table->index('scheduled_at');
            $table->index(['candidate_stage_id', 'scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropIndex(['candidate_stage_id']);
            $table->dropIndex(['created_by']);
            $table->dropIndex(['scheduled_at']);
            $table->dropIndex(['candidate_stage_id', 'scheduled_at']);
        });
    }
};

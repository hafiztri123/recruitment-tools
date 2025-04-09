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
        Schema::table('candidate_progresses', function (Blueprint $table) {
            $table->index('candidate_id');
            $table->index('recruitment_batch_id');
            $table->index(['candidate_id', 'recruitment_batch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_progresses', function (Blueprint $table) {
            $table->dropIndex(['candidate_id']);
            $table->dropIndex('recruitment_batch_id');
            $table->dropIndex(['candidate_id', 'recruitment_batch_id']);
        });
    }
};

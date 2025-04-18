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
        Schema::table('interviewers', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('interview_id');
            $table->index(['user_id', 'interview_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interviewers', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['interview_id']);
            $table->dropIndex(['user_id', 'interview_id']);
        });
    }
};

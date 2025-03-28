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
        Schema::rename('candidate_progress', 'candidate_progresses');
            //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('candidate_progresses', 'candidate_progress');
    }
};

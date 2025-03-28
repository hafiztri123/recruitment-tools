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
        Schema::create('candidate_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recruitment_batch_id');
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('candidate_stage_id');
            $table->foreign('recruitment_batch_id')->references('id')->on('recruitment_batches')->cascadeOnDelete();
            $table->foreign('candidate_id')->references('id')->on('candidates')->cascadeOnDelete();
            $table->foreign('candidate_stage_id')->references('id')->on('candidate_stages')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_recruitment_batches');
    }
};

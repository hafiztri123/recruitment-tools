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
        Schema::create('candidate_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->foreign('candidate_id')->references('id')->on('candidates')->cascadeOnDelete();
            $table->unsignedBigInteger('stage_id');
            $table->foreign('stage_id')->references('id')->on('recruitment_stages')->cascadeOnDelete();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed'])->default('pending');
            $table->dateTime('scheduled_at');
            $table->dateTime('completed_at');
            $table->text('feedback');
            $table->boolean('passed');
            $table->text('rejection_reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_stages');
    }
};

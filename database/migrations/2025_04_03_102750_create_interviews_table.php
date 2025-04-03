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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_stage_id');
            $table->foreign('candidate_stage_id')->references('id')->on('candidate_stages')->cascadeOnDelete();
            $table->timestamp('scheduled_at');
            $table->integer('duration_minutes')->nullable()->default(60);
            $table->string('location')->nullable();
            $table->string('meeting_link')->nullable();
            $table->string('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};

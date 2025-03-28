<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('candidate_stages', function (Blueprint $table) {
            $table->dateTime('scheduled_at')->nullable()->change();
            $table->dateTime('completed_at')->nullable()->change();
            $table->text('feedback')->nullable()->change();
            $table->boolean('passed')->nullable()->change();
            $table->text('rejection_reason')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('candidate_stages', function (Blueprint $table) {
            $table->dateTime('scheduled_at')->nullable(false)->change();
            $table->dateTime('completed_at')->nullable(false)->change();
            $table->text('feedback')->nullable(false)->change();
            $table->boolean('passed')->nullable(false)->change();
            $table->text('rejection_reason')->nullable(false)->change();
        });
    }
};

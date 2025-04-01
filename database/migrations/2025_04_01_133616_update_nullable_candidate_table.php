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
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->change();
            $table->string('whatsapp', 20)->nullable()->change();
            $table->string('resume_path')->nullable()->change();
            $table->string('source')->nullable()->change();
            $table->text('notes')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->string('phone', 20)->nullable(false)->change();
            $table->string('whatsapp', 20)->nullable(false)->change();
            $table->string('resume_path')->nullable(false)->change();
            $table->string('source')->nullable(false)->change();
            $table->text('notes')->nullable(false)->change();
        });
    }
};

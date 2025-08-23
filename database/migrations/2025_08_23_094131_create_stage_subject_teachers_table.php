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
        Schema::create('stage_subject_teachers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stage_id')->nullable();
            $table->bigInteger('subject_id')->nullable();
            $table->bigInteger('teacher_id')->nullable();
            $table->timestamps();

            $table->unique(['stage_id', 'subject_id', 'teacher_id']); // quickly index and unique to faster query
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stage_subject_teachers');
    }
};

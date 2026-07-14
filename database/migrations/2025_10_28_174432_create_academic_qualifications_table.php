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
        Schema::create('academic_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('university')->nullable();
            $table->string('level')->nullable();
            $table->string('institute')->nullable();
            $table->string('subjects')->nullable();
            $table->string('obtained_marks')->nullable();
            $table->string('obtained_percentage')->nullable();
            $table->string('completed_year')->nullable();
            $table->string('marksheet')->nullable();
            $table->string('certificate')->nullable();
            $table->string('equivalent')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_qualifications');
    }
};

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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('project_title');
            $table->string('description')->nullable();
            $table->string('makes_from')->nullable();
            $table->string('makes_for')->nullable();
            $table->string('your_role')->nullable();
            $table->string('used_technologies')->nullable();
            $table->datetime('worked_from')->nullable();
            $table->datetime('worked_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

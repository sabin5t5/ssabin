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
        Schema::create('people', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');

            
            $table->string('title')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable();
            
            $table->string('language')->nullable();
            $table->string('born_country')->nullable();
            $table->string('mailing_address')->nullable();
            $table->string('current_address')->nullable();
            $table->string('country')->nullable();
            $table->string('rank')->nullable();

            $table->text('remarks')->nullable();
            $table->string('icon')->nullable();

            $table->string('email')->nullable();
            $table->string('work_email')->nullable();

            $table->string('social_link')->nullable();
            $table->string('timezone')->nullable();
            $table->string('website')->nullable();

            $table->string('work_phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('home_phone')->nullable();

            $table->string('image')->nullable();

            $table->string('marital_status')->nullable();
            $table->string('religion')->nullable();

            $table->string('nid_ssn')->nullable();
            $table->string('passport_number')->nullable();

            $table->boolean('disability_status')->nullable();
            $table->string('work_authorization')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};

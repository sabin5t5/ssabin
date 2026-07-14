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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('blog_category')->constrained('blog_categories');
            $table->dateTime('published_at');
            $table->dateTime('archive_at')->nullable();
            $table->string('slug');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('video_link')->nullable();
            $table->tinyInteger('status')->unsigned();
            $table->integer('is_highlight')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });
        Schema::create('tag_blogs', function (Blueprint $table) {
            $table->foreignId('blog_id')
                ->constrained('blogs');
            $table->foreignId('tag_id')
                ->constrained('tags');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_blogs');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('blogs');
    }
};

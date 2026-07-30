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
        Schema::create('course_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('title');
            $table->text('video_url'); // External URL - video-player link (can be very long)
            $table->text('subtitle_url')->nullable(); // External URL - subtitle link (can be very long)
            $table->enum('type', ['video', 'document'])->default('video');
            $table->string('duration')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_free')->default(false);
            $table->integer('views_count')->default(0);
            $table->timestamps();
            
            $table->index(['course_id', 'order']);
            $table->index('section_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_videos');
    }
};

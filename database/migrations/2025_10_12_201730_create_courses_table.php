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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image_url')->nullable(); // External URL
            $table->string('duration')->nullable(); // e.g., "1:19:25"
            $table->string('source')->nullable(); // e.g., "Udemy"
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->longText('what_you_learn')->nullable();
            $table->longText('who_this_for')->nullable();
            $table->longText('prerequisites')->nullable();
            $table->json('info')->nullable(); // زبان، جلسات، تاریخ انتشار و غیره
            $table->integer('sessions_count')->default(0);
            $table->string('language')->default('fa');
            $table->date('published_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('enrollments_count')->default(0);
            $table->timestamps();
            
            $table->index(['slug', 'is_active']);
            $table->index('is_featured');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

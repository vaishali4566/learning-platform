<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title', 255);
            $table->enum('content_type', ['video', 'text', 'quiz']);
            $table->string('video_url', 255)->nullable();
            $table->longText('text_content')->nullable();
            $table->json('quiz_questions')->nullable();
            $table->integer('order_number');
            $table->string('duration', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
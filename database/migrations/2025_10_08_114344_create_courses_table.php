<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainer_id')->constrained('trainers')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('duration', 50)->nullable();
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced']);
            $table->string('thumbnail', 255)->nullable();
            $table->boolean('is_online')->default(true);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
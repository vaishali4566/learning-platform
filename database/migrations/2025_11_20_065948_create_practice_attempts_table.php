<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('practice_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('practice_test_id')->constrained()->onDelete('cascade');

            $table->integer('total_questions')->default(0);
            $table->integer('correct_answers')->default(0);

            $table->decimal('score', 5, 2)->default(0); // % score
            $table->integer('time_taken')->default(0);   // seconds

            $table->enum('status', ['in_progress', 'completed'])->default('in_progress');

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practice_attempts');
    }
};

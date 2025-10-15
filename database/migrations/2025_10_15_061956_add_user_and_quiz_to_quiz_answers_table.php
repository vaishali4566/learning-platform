<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quiz_answers', function (Blueprint $table) {
            // Add quiz_id to know which quiz this answer belongs to
            if (!Schema::hasColumn('quiz_answers', 'quiz_id')) {
                $table->foreignId('quiz_id')->nullable()->after('id')->constrained('quizzes')->onDelete('cascade');
            }

            // Add user_id to know who selected this answer
            if (!Schema::hasColumn('quiz_answers', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('quiz_id')->constrained('users')->onDelete('cascade');
            }

            // Optional: add selected_option index (0..3) if needed
            if (!Schema::hasColumn('quiz_answers', 'selected_option')) {
                $table->unsignedTinyInteger('selected_option')->nullable()->after('is_correct');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quiz_answers', function (Blueprint $table) {
            $table->dropForeign(['quiz_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['quiz_id', 'user_id', 'selected_option']);
        });
    }
};

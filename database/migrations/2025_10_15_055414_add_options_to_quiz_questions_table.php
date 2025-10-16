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
        Schema::table('quiz_questions', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('quiz_questions', 'options')) {
                $table->json('options')->nullable()->after('marks');
            }

            // Add correct_option column (0..3)
            if (!Schema::hasColumn('quiz_questions', 'correct_option')) {
                $table->unsignedTinyInteger('correct_option')->nullable()->after('options');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_questions', function (Blueprint $table) {
            //
            $table->dropColumn(['options', 'correct_option']);
        });
    }
};

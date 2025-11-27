<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Quiz table
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('source')->nullable()->after('title'); 
        });

        // Practice Test table
        Schema::table('practice_tests', function (Blueprint $table) {
            $table->string('source')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn('source');
        });

        Schema::table('practice_tests', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};

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
        Schema::table('lessons', function (Blueprint $table) {
            $table->unsignedBigInteger('practice_test_id')->nullable()->after('content_type');
            $table->foreign('practice_test_id')->references('id')->on('practice_tests')->onDelete('set null');
        
        });
    }

    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropForeign(['practice_test_id']);
            $table->dropColumn('practice_test_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('trainer_id')->nullable()->after('user_id');
            $table->string('buyer_type')->nullable()->after('trainer_id'); // 'user' or 'trainer'
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['trainer_id', 'buyer_type']);
        });
    }
};

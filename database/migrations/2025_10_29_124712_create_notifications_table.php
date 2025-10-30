<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications_custom', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();      // for normal user
            $table->unsignedBigInteger('trainer_id')->nullable();   // for trainer
            $table->unsignedBigInteger('admin_id')->nullable();     // for admin
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('type')->nullable();                     // success, info, warning, etc.
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_custom');
    }
};

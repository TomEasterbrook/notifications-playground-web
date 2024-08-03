<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notification_devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->foreignIdFor(\App\Models\User::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_devices');
    }
};

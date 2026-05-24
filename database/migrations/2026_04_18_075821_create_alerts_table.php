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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->string('device_id');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->string('sensor_type');
            $table->float('value');
            $table->enum('condition', ['above_max', 'below_min']);
            $table->enum('status', ['resolved', 'unresolved'])->default('unresolved');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};

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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sensor_key_id');
            $table->float('n')->nullable();
            $table->float('p')->nullable();
            $table->float('k')->nullable();
            $table->float('ph')->nullable();
            $table->float('ec')->nullable();
            $table->float('temperature')->nullable();
            $table->float('humidity')->nullable();
            $table->timestamps();

            $table->foreign('sensor_key_id')->references('id')->on('sensor_keys');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};

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
        Schema::create('sensor_tests', function (Blueprint $table) {
            $table->id();
            $table->float('n')->nullable();
            $table->float('p')->nullable();
            $table->float('k')->nullable();
            $table->float('ph')->nullable();
            $table->float('ec')->nullable();
            $table->float('temperature')->nullable();
            $table->float('humidity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_tests');
    }
};

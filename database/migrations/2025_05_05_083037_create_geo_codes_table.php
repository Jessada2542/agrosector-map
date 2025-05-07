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
        Schema::create('geo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('province_code')->nullable();
            $table->string('province_name_th')->nullable();
            $table->string('province_name_en')->nullable();
            $table->string('district_code')->nullable();
            $table->string('district_name_th')->nullable();
            $table->string('district_name_en')->nullable();
            $table->string('subdistrict_code')->nullable();
            $table->string('subdistrict_name_th')->nullable();
            $table->string('subdistrict_name_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_codes');
    }
};

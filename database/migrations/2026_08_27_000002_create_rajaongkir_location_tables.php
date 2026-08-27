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
        Schema::create('rajaongkir_provinces', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('name');
            $table->string('name_lower')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('rajaongkir_cities', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('province_id')->index();
            $table->string('name');
            $table->string('name_lower')->nullable()->index();
            $table->string('zip_code')->nullable();
            $table->timestamps();

            $table->unique(['province_id', 'name']);
        });

        Schema::create('rajaongkir_districts', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->unsignedInteger('city_id')->index();
            $table->string('name');
            $table->string('name_lower')->nullable()->index();
            $table->string('zip_code')->nullable();
            $table->timestamps();

            $table->unique(['city_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rajaongkir_districts');
        Schema::dropIfExists('rajaongkir_cities');
        Schema::dropIfExists('rajaongkir_provinces');
    }
};

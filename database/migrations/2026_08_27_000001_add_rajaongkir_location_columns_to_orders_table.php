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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('province')->nullable()->after('city');
            $table->string('shipping_district_id')->nullable()->after('province');
            $table->unsignedInteger('weight_grams')->nullable()->after('shipping_district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['province', 'shipping_district_id', 'weight_grams']);
        });
    }
};

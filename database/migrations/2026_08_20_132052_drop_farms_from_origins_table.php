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
        Schema::table('origins', function (Blueprint $table) {
            $table->dropColumn('farms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('origins', function (Blueprint $table) {
            $table->json('farms')->nullable()->after('flavor');
        });
    }
};

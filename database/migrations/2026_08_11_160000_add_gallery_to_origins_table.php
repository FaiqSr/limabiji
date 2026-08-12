<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('origins', function (Blueprint $table) {
            if (! Schema::hasColumn('origins', 'gallery')) {
                $table->json('gallery')->nullable()->after('farms');
            }
        });
    }

    public function down(): void
    {
        Schema::table('origins', function (Blueprint $table) {
            $table->dropColumn('gallery');
        });
    }
};

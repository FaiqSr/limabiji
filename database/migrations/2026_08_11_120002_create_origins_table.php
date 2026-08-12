<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('origins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('province');
            $table->string('image')->nullable();
            $table->string('altitude');
            $table->string('varietals');
            $table->string('process');
            $table->string('harvest');
            $table->string('score');
            $table->text('overview');                    // English
            $table->text('overview_id')->nullable();     // Indonesian
            $table->json('flavor')->nullable();          // ["Note 1", "Note 2"]
            $table->json('farms')->nullable();           // ["Farm A", "Farm B"]
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('origins');
    }
};

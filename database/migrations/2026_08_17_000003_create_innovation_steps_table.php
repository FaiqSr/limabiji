<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('innovation_steps', function (Blueprint $table) {
            $table->id();
            $table->string('step_number', 10)->nullable(); // e.g. '01', '02'
            $table->string('title');
            $table->string('title_id')->nullable();
            $table->text('description');
            $table->text('description_id')->nullable();
            $table->text('details')->nullable(); // Comma-separated tag pills in EN
            $table->text('details_id')->nullable(); // Comma-separated tag pills in ID
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('innovation_steps');
    }
};

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
            $table->string('payment_method_type')->nullable()->index()->after('payment_method');
            $table->string('midtrans_transaction_id')->nullable()->after('payment_status');
            $table->string('midtrans_status')->nullable()->after('midtrans_transaction_id');
            $table->json('payment_instructions')->nullable()->after('midtrans_status');
            $table->timestamp('expires_at')->nullable()->after('payment_instructions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method_type', 'midtrans_transaction_id', 'midtrans_status', 'payment_instructions', 'expires_at']);
        });
    }
};

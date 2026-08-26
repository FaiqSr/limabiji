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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_id')->nullable();
            $table->string('slug')->unique();
            $table->string('category')->index(); // arabika, robusta, blend, experimental
            $table->text('description')->nullable();
            $table->text('description_id')->nullable();
            $table->string('origin')->nullable();
            $table->string('altitude')->nullable();
            $table->string('process')->nullable();
            $table->string('roast_level')->default('medium'); // light, medium, medium_dark, dark
            $table->string('sca_score')->nullable();
            $table->json('tasting_notes')->nullable();
            $table->json('flavor_tags')->nullable();
            $table->json('recommended_brews')->nullable(); // v60, espresso, tubruk, cold_brew, french_press
            $table->string('image')->nullable();
            $table->unsignedInteger('base_price_200g')->default(75000);
            $table->unsignedInteger('price_500g')->default(175000);
            $table->unsignedInteger('price_1kg')->default(320000);
            $table->unsignedInteger('stock')->default(100);
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique()->index();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->string('city');
            $table->string('postal_code')->nullable();
            $table->string('courier')->default('JNE REG');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->unsignedBigInteger('shipping_cost')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->string('payment_method')->default('midtrans');
            $table->string('payment_status')->default('pending')->index(); // pending, paid, failed, expired
            $table->string('snap_token')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('weight')->default('200g'); // 200g, 500g, 1kg
            $table->string('grind_size')->default('whole_bean'); // whole_bean, coarse, medium, fine
            $table->unsignedBigInteger('unit_price')->default(0);
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedBigInteger('subtotal')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
    }
};

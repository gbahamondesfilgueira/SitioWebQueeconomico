<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->enum('status', ['active', 'converted', 'abandoned', 'expired'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_session_id')->constrained()->cascadeOnDelete();
            $table->enum('item_type', ['product', 'pack']);
            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('product_pack_id')->nullable()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('regular_price', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('final_unit_price', 12, 2);
            $table->decimal('line_subtotal', 12, 2);
            $table->decimal('line_discount', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2);
            $table->json('applied_rules')->nullable();
            $table->foreignId('stock_reservation_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cart_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->string('coupon_code');
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
            $table->unique(['cart_session_id', 'coupon_id']);
        });

        Schema::create('checkout_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_session_id')->constrained()->cascadeOnDelete();
            $table->enum('address_type', ['shipping', 'billing']);
            $table->foreignId('customer_address_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contact_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('country')->default('Chile');
            $table->string('region');
            $table->string('commune');
            $table->string('city');
            $table->string('street');
            $table->string('number');
            $table->string('apartment')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('reference')->nullable();
            $table->timestamps();
        });

        Schema::create('checkout_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_session_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method');
            $table->string('payment_label');
            $table->enum('status', ['selected', 'pending', 'failed'])->default('selected');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('checkout_shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_session_id')->constrained()->cascadeOnDelete();
            $table->string('carrier_name')->nullable();
            $table->string('service_name');
            $table->enum('shipping_type', ['delivery', 'pickup'])->default('delivery');
            $table->decimal('estimated_price', 12, 2)->default(0);
            $table->unsignedInteger('estimated_days')->nullable();
            $table->enum('status', ['selected', 'pending'])->default('selected');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checkout_shipping_methods');
        Schema::dropIfExists('checkout_payment_methods');
        Schema::dropIfExists('checkout_addresses');
        Schema::dropIfExists('cart_coupons');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('cart_sessions');
    }
};

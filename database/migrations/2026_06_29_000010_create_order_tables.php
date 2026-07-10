<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cart_session_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('order_status', ['pending', 'confirmed', 'paid', 'preparing', 'ready_to_ship', 'shipped', 'delivered', 'completed', 'cancelled', 'refunded'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'failed', 'refunded'])->default('pending');
            $table->enum('fulfillment_status', ['pending', 'picking', 'packed', 'ready', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('subtotal_regular', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('item_discount_total', 12, 2)->default(0);
            $table->decimal('coupon_discount_total', 12, 2)->default(0);
            $table->decimal('shipping_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->string('currency')->default('CLP');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('customer_name');
            $table->string('customer_rut')->nullable();
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->enum('item_type', ['product', 'pack']);
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_pack_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('variant_name')->nullable();
            $table->string('sku')->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('regular_unit_price', 12, 2)->default(0);
            $table->decimal('final_unit_price', 12, 2)->default(0);
            $table->decimal('line_subtotal', 12, 2)->default(0);
            $table->decimal('line_discount', 12, 2)->default(0);
            $table->decimal('line_tax', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->json('applied_rules')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('order_item_pack_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('variant_name')->nullable();
            $table->string('sku')->nullable();
            $table->unsignedInteger('quantity_per_pack');
            $table->unsignedInteger('total_quantity');
            $table->timestamps();
        });

        Schema::create('order_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->enum('address_type', ['shipping', 'billing']);
            $table->string('contact_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('country');
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

        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('payment_method');
            $table->string('payment_label');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->decimal('amount', 12, 2);
            $table->string('transaction_id')->nullable();
            $table->string('authorization_code')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('order_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('carrier_name')->nullable();
            $table->string('service_name');
            $table->enum('shipping_type', ['delivery', 'pickup'])->default('delivery');
            $table->enum('shipping_status', ['pending', 'preparing', 'ready', 'shipped', 'delivered', 'returned', 'cancelled'])->default('pending');
            $table->string('tracking_number')->nullable();
            $table->string('tracking_url')->nullable();
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->unsignedInteger('estimated_days')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->enum('status_type', ['order', 'payment', 'fulfillment', 'shipping']);
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('order_fulfillments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'picking', 'picked', 'packing', 'packed', 'ready', 'cancelled'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('order_fulfillment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_fulfillment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('required_quantity', 12, 3);
            $table->decimal('picked_quantity', 12, 3)->default(0);
            $table->decimal('packed_quantity', 12, 3)->default(0);
            $table->enum('status', ['pending', 'picked', 'packed', 'missing'])->default('pending');
            $table->timestamps();
        });

        Schema::create('order_cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->foreignId('cancelled_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('cancelled_at');
            $table->boolean('restore_stock')->default(true);
            $table->timestamps();
        });

        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('return_number')->unique();
            $table->enum('status', ['requested', 'approved', 'rejected', 'received', 'refunded', 'closed'])->default('requested');
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_return_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->enum('condition', ['new', 'opened', 'damaged', 'missing'])->default('new');
            $table->boolean('restock')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_return_items');
        Schema::dropIfExists('order_returns');
        Schema::dropIfExists('order_cancellations');
        Schema::dropIfExists('order_fulfillment_items');
        Schema::dropIfExists('order_fulfillments');
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_shipments');
        Schema::dropIfExists('order_payments');
        Schema::dropIfExists('order_addresses');
        Schema::dropIfExists('order_item_pack_components');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};

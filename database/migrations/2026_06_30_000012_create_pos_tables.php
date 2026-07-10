<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('pos_manual_discount_without_approval', 5, 2)->default(5);
            $table->unsignedInteger('pos_reservation_minutes')->default(60);
            $table->string('pos_default_receipt_message')->default('Gracias por su compra');
        });

        Schema::create('pos_terminals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('warehouse_locations')->nullOnDelete();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->enum('order_channel', ['ecommerce', 'pos', 'manual'])->default('ecommerce')->after('cart_session_id');
            $table->foreignId('pos_terminal_id')->nullable()->after('order_channel')->constrained('pos_terminals')->nullOnDelete();
            $table->foreignId('sold_by')->nullable()->after('pos_terminal_id')->constrained('users')->nullOnDelete();
            $table->string('source_reference')->nullable()->after('sold_by');
        });

        Schema::create('pos_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_terminal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['active', 'converted', 'cancelled', 'abandoned'])->default('active');
            $table->decimal('subtotal_regular', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('item_discount_total', 12, 2)->default(0);
            $table->decimal('coupon_discount_total', 12, 2)->default(0);
            $table->decimal('manual_discount_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->string('currency')->default('CLP');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_cart_id')->constrained()->cascadeOnDelete();
            $table->enum('item_type', ['product', 'pack']);
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_pack_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('regular_unit_price', 12, 2)->default(0);
            $table->decimal('final_unit_price', 12, 2)->default(0);
            $table->decimal('line_subtotal', 12, 2)->default(0);
            $table->decimal('line_discount', 12, 2)->default(0);
            $table->decimal('line_tax', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->json('applied_rules')->nullable();
            $table->decimal('manual_discount_amount', 12, 2)->default(0);
            $table->string('manual_discount_reason')->nullable();
            $table->foreignId('stock_reservation_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pos_manual_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pos_cart_item_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->string('reason');
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        Schema::create('pos_cart_coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->string('coupon_code');
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->timestamp('applied_at')->nullable();
            $table->timestamps();
            $table->unique(['pos_cart_id', 'coupon_id']);
        });

        Schema::create('pos_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('payment_type', ['cash', 'debit_card', 'credit_card', 'bank_transfer', 'other']);
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_reference')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pos_cart_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained('pos_payment_methods')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->foreignId('pos_terminal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['draft', 'sent', 'accepted', 'converted', 'cancelled'])->default('draft');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pos_quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_quote_id')->constrained()->cascadeOnDelete();
            $table->enum('item_type', ['product', 'pack']);
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_pack_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->decimal('regular_unit_price', 12, 2)->default(0);
            $table->decimal('final_unit_price', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->json('applied_rules')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_number')->unique();
            $table->foreignId('pos_terminal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['active', 'consumed', 'released', 'expired', 'cancelled'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('pos_reservation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_reservation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->foreignId('stock_reservation_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_reservation_items');
        Schema::dropIfExists('pos_reservations');
        Schema::dropIfExists('pos_quote_items');
        Schema::dropIfExists('pos_quotes');
        Schema::dropIfExists('pos_cart_payments');
        Schema::dropIfExists('pos_payment_methods');
        Schema::dropIfExists('pos_cart_coupons');
        Schema::dropIfExists('pos_manual_discounts');
        Schema::dropIfExists('pos_cart_items');
        Schema::dropIfExists('pos_carts');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sold_by');
            $table->dropConstrainedForeignId('pos_terminal_id');
            $table->dropColumn(['order_channel', 'source_reference']);
        });
        Schema::dropIfExists('pos_terminals');
        Schema::table('settings', fn (Blueprint $table) => $table->dropColumn(['pos_manual_discount_without_approval', 'pos_reservation_minutes', 'pos_default_receipt_message']));
    }
};

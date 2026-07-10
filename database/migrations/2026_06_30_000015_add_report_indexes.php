<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('created_at', 'orders_created_idx');
            $table->index('order_channel', 'orders_channel_idx');
            $table->index('order_status', 'orders_status_idx');
            $table->index('payment_status', 'orders_payment_idx');
            $table->index('sold_by', 'orders_sold_by_idx');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index('product_id', 'order_items_product_idx');
            $table->index('product_variant_id', 'order_items_variant_idx');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index('created_at', 'stock_movements_created_idx');
            $table->index('movement_type', 'stock_movements_type_idx');
        });

        Schema::table('cash_movements', function (Blueprint $table) {
            $table->index('created_at', 'cash_movements_created_idx');
            $table->index('movement_type', 'cash_movements_type_idx');
            $table->index('payment_method_id', 'cash_movements_payment_idx');
        });
    }

    public function down(): void
    {
        Schema::table('cash_movements', function (Blueprint $table) {
            $table->dropIndex('cash_movements_created_idx');
            $table->dropIndex('cash_movements_type_idx');
            $table->dropIndex('cash_movements_payment_idx');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex('stock_movements_created_idx');
            $table->dropIndex('stock_movements_type_idx');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex('order_items_product_idx');
            $table->dropIndex('order_items_variant_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_created_idx');
            $table->dropIndex('orders_channel_idx');
            $table->dropIndex('orders_status_idx');
            $table->dropIndex('orders_payment_idx');
            $table->dropIndex('orders_sold_by_idx');
        });
    }
};

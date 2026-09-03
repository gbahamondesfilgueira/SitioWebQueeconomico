<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->boolean('is_central')->default(false)->after('is_active');
            $table->unsignedInteger('fulfillment_priority')->default(100)->after('is_central');
        });

        Schema::table('warehouse_locations', function (Blueprint $table) {
            $table->boolean('is_sellable')->default(true)->after('is_active');
        });

        Schema::table('cart_sessions', function (Blueprint $table) {
            $table->string('destination_region_code', 80)->nullable()->after('status')->index();
        });

        Schema::table('checkout_shipping_methods', function (Blueprint $table) {
            $table->unsignedInteger('estimated_days_min')->nullable()->after('estimated_days');
            $table->unsignedInteger('estimated_days_max')->nullable()->after('estimated_days_min');
        });

        Schema::table('order_shipments', function (Blueprint $table) {
            $table->unsignedInteger('estimated_days_min')->nullable()->after('estimated_days');
            $table->unsignedInteger('estimated_days_max')->nullable()->after('estimated_days_min');
        });

        Schema::table('order_fulfillment_items', function (Blueprint $table) {
            $table->foreignId('warehouse_location_id')->nullable()->after('product_variant_id')->constrained()->nullOnDelete();
            $table->foreignId('stock_reservation_id')->nullable()->after('warehouse_location_id')->constrained()->nullOnDelete();
        });

        Schema::table('stock_levels', function (Blueprint $table) {
            $table->string('source_key', 150)->nullable()->after('product_variant_id');
        });

        $duplicates = DB::table('stock_levels')
            ->selectRaw('warehouse_id, warehouse_location_id, product_id, product_variant_id, COUNT(*) as total')
            ->groupBy('warehouse_id', 'warehouse_location_id', 'product_id', 'product_variant_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $query = DB::table('stock_levels')
                ->where('warehouse_id', $duplicate->warehouse_id)
                ->where('product_id', $duplicate->product_id)
                ->when($duplicate->warehouse_location_id === null, fn ($q) => $q->whereNull('warehouse_location_id'), fn ($q) => $q->where('warehouse_location_id', $duplicate->warehouse_location_id))
                ->when($duplicate->product_variant_id === null, fn ($q) => $q->whereNull('product_variant_id'), fn ($q) => $q->where('product_variant_id', $duplicate->product_variant_id));
            $rows = (clone $query)->orderBy('id')->get();
            $keeper = $rows->first();
            DB::table('stock_levels')->where('id', $keeper->id)->update([
                'physical_stock' => $rows->sum('physical_stock'),
                'reserved_stock' => $rows->sum('reserved_stock'),
                'minimum_stock' => $rows->max('minimum_stock'),
                'maximum_stock' => $rows->max('maximum_stock'),
            ]);
            DB::table('stock_levels')->whereIn('id', $rows->skip(1)->pluck('id'))->delete();
        }

        DB::table('stock_levels')->orderBy('id')->each(function ($level) {
            DB::table('stock_levels')->where('id', $level->id)->update([
                'source_key' => implode(':', [$level->warehouse_id, $level->warehouse_location_id ?? 0, $level->product_id, $level->product_variant_id ?? 0]),
            ]);
        });

        Schema::table('stock_levels', function (Blueprint $table) {
            $table->unique('source_key', 'stock_levels_source_key_unique');
            $table->index(['warehouse_id', 'product_id', 'product_variant_id'], 'stock_levels_allocation_index');
        });

        DB::table('warehouses')->where('code', 'ECOM')->update([
            'region' => 'Región Metropolitana',
            'is_central' => true,
            'fulfillment_priority' => 1,
        ]);
        DB::table('warehouse_locations')->whereIn('code', ['DEV', 'MERMA'])->update(['is_sellable' => false]);
    }

    public function down(): void
    {
        Schema::table('stock_levels', function (Blueprint $table) {
            $table->dropUnique('stock_levels_source_key_unique');
            $table->dropIndex('stock_levels_allocation_index');
            $table->dropColumn('source_key');
        });
        Schema::table('order_fulfillment_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('stock_reservation_id');
            $table->dropConstrainedForeignId('warehouse_location_id');
        });
        Schema::table('order_shipments', fn (Blueprint $table) => $table->dropColumn(['estimated_days_min', 'estimated_days_max']));
        Schema::table('checkout_shipping_methods', fn (Blueprint $table) => $table->dropColumn(['estimated_days_min', 'estimated_days_max']));
        Schema::table('cart_sessions', function (Blueprint $table) {
            $table->dropIndex(['destination_region_code']);
            $table->dropColumn('destination_region_code');
        });
        Schema::table('warehouse_locations', fn (Blueprint $table) => $table->dropColumn('is_sellable'));
        Schema::table('warehouses', fn (Blueprint $table) => $table->dropColumn(['is_central', 'fulfillment_priority']));
    }
};

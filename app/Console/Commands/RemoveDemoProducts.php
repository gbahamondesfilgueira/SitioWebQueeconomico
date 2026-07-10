<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductPack;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveDemoProducts extends Command
{
    protected $signature = 'store:remove-demo-products {--force : Ejecuta la limpieza sin pedir confirmacion}';

    protected $description = 'Elimina productos, variantes, stock y packs de demostracion creados para pruebas.';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Esto eliminara los productos demo QE-ZAPATO-DEMO, QE-BOTA-DEMO y el pack demo. Continuar?')) {
            $this->info('Operacion cancelada.');
            return self::SUCCESS;
        }

        DB::transaction(function () {
            $productIds = Product::withTrashed()
                ->whereIn('sku', ['QE-ZAPATO-DEMO', 'QE-BOTA-DEMO'])
                ->orWhereIn('slug', ['zapato-casual-demo', 'bota-mujer-impermeable-demo'])
                ->pluck('id');

            $variantIds = DB::table('product_variants')
                ->whereIn('product_id', $productIds)
                ->orWhere('sku', 'like', 'QE-BOTA-NEGRO-%')
                ->pluck('id');

            $packIds = ProductPack::withTrashed()
                ->whereIn('sku', ['QE-PACK-CALZADO'])
                ->orWhere('slug', 'pack-calzado-demo')
                ->pluck('id');

            $this->deleteWhere('product_variant_attribute_values', fn ($query) => $query->whereIn('product_variant_id', $variantIds));
            $this->deleteProductReferences('stock_levels', $productIds, $variantIds);
            $this->deleteProductReferences('stock_movements', $productIds, $variantIds);
            $this->deleteProductReferences('stock_reservations', $productIds, $variantIds);
            $this->deleteProductReferences('stock_adjustments', $productIds, $variantIds);
            $this->deleteProductReferences('stock_transfer_items', $productIds, $variantIds);
            $this->deleteProductPackReferences('product_pack_items', $productIds, $variantIds, $packIds);
            $this->deleteWhere('related_products', fn ($query) => $query->whereIn('product_id', $productIds)->orWhereIn('related_product_id', $productIds));
            $this->deleteWhere('product_tag', fn ($query) => $query->whereIn('product_id', $productIds));
            $this->deleteProductReferences('product_images', $productIds, $variantIds);
            $this->deleteProductPackReferences('cart_items', $productIds, $variantIds, $packIds);
            $this->deleteProductPackReferences('pos_cart_items', $productIds, $variantIds, $packIds);
            $this->deleteProductPackReferences('pos_quote_items', $productIds, $variantIds, $packIds);
            $this->deleteProductReferences('pos_reservation_items', $productIds, $variantIds);
            $this->deleteProductReferences('promotion_products', $productIds, $variantIds);
            $this->deleteProductReferences('coupon_products', $productIds, $variantIds);
            $this->deleteProductReferences('price_list_items', $productIds, $variantIds);
            $this->deleteProductReferences('quantity_discounts', $productIds, $variantIds);
            $this->deleteWhere('wishlist_items', fn ($query) => $query->whereIn('product_id', $productIds)->orWhereIn('variant_id', $variantIds));
            $this->deleteProductReferences('customer_favorites', $productIds, $variantIds);
            $this->deleteProductReferences('external_product_mappings', $productIds, $variantIds);
            $this->deleteProductReferences('order_item_pack_components', $productIds, $variantIds);
            $this->deleteProductReferences('order_fulfillment_items', $productIds, $variantIds);
            $this->nullOrderItemReferences($productIds, $variantIds, $packIds);
            $this->nullPromotionGiftReferences($productIds, $variantIds);

            DB::table('product_variants')->whereIn('id', $variantIds)->delete();
            ProductPack::withTrashed()->whereIn('id', $packIds)->forceDelete();
            Product::withTrashed()->whereIn('id', $productIds)->forceDelete();
        });

        $this->info('Productos demo eliminados correctamente.');

        return self::SUCCESS;
    }

    private function deleteProductReferences(string $table, $productIds, $variantIds): void
    {
        $this->deleteWhere($table, fn ($query) => $query
            ->whereIn('product_id', $productIds)
            ->orWhereIn('product_variant_id', $variantIds)
        );
    }

    private function deleteProductPackReferences(string $table, $productIds, $variantIds, $packIds): void
    {
        $this->deleteWhere($table, fn ($query) => $query
            ->whereIn('product_id', $productIds)
            ->orWhereIn('product_variant_id', $variantIds)
            ->orWhereIn('product_pack_id', $packIds)
        );
    }

    private function deleteWhere(string $table, callable $callback): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        DB::table($table)->where($callback)->delete();
    }

    private function nullOrderItemReferences($productIds, $variantIds, $packIds): void
    {
        if (! Schema::hasTable('order_items')) {
            return;
        }

        DB::table('order_items')
            ->where(fn ($query) => $query
                ->whereIn('product_id', $productIds)
                ->orWhereIn('product_variant_id', $variantIds)
                ->orWhereIn('product_pack_id', $packIds)
            )
            ->update([
                'product_id' => null,
                'product_variant_id' => null,
                'product_pack_id' => null,
            ]);
    }

    private function nullPromotionGiftReferences($productIds, $variantIds): void
    {
        if (! Schema::hasTable('promotions')) {
            return;
        }

        DB::table('promotions')
            ->whereIn('gift_product_id', $productIds)
            ->update(['gift_product_id' => null, 'gift_product_variant_id' => null]);

        DB::table('promotions')
            ->whereIn('gift_product_variant_id', $variantIds)
            ->update(['gift_product_variant_id' => null]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('currency', 10)->default('CLP');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('price_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_list_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('price', 12, 2);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->text('description')->nullable();
            $table->enum('promotion_type', ['percentage_discount', 'fixed_discount', 'fixed_price', 'quantity_discount', 'buy_x_get_y', 'free_shipping', 'gift_product', 'bundle_discount']);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('is_stackable')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_count')->default(0);
            $table->decimal('min_subtotal', 12, 2)->nullable();
            $table->integer('min_quantity')->nullable();
            $table->string('customer_role')->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->nullable();
            $table->decimal('fixed_price', 12, 2)->nullable();
            $table->integer('buy_quantity')->nullable();
            $table->integer('get_quantity')->nullable();
            $table->foreignId('gift_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('gift_product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        foreach ([
            'promotion_products' => ['product_id' => 'products', 'product_variant_id' => 'product_variants'],
            'promotion_categories' => ['category_id' => 'categories'],
            'promotion_brands' => ['brand_id' => 'brands'],
            'promotion_tags' => ['tag_id' => 'tags'],
        ] as $tableName => $relations) {
            Schema::create($tableName, function (Blueprint $table) use ($relations) {
                $table->id();
                $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
                foreach ($relations as $column => $relatedTable) {
                    $column === 'product_variant_id'
                        ? $table->foreignId($column)->nullable()->constrained($relatedTable)->cascadeOnDelete()
                        : $table->foreignId($column)->constrained($relatedTable)->cascadeOnDelete();
                }
                $table->timestamps();
            });
        }

        Schema::create('product_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->string('barcode')->nullable()->unique();
            $table->text('description')->nullable();
            $table->decimal('regular_price', 12, 2)->nullable();
            $table->decimal('pack_price', 12, 2);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_visible')->default(false);
            $table->string('image_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_pack_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_pack_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->timestamps();
            $table->unique(['product_pack_id', 'product_id', 'product_variant_id'], 'pack_item_unique');
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed', 'free_shipping']);
            $table->decimal('discount_value', 12, 2)->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('usage_count')->default(0);
            $table->integer('usage_limit_per_customer')->nullable();
            $table->decimal('min_subtotal', 12, 2)->nullable();
            $table->integer('min_quantity')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('coupon_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('coupon_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->dateTime('used_at');
            $table->decimal('discount_amount', 12, 2);
            $table->timestamps();
        });

        Schema::create('quantity_discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('min_quantity');
            $table->integer('max_quantity')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed', 'fixed_price']);
            $table->decimal('discount_value', 12, 2);
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quantity_discounts');
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupon_categories');
        Schema::dropIfExists('coupon_products');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('product_pack_items');
        Schema::dropIfExists('product_packs');
        Schema::dropIfExists('promotion_tags');
        Schema::dropIfExists('promotion_brands');
        Schema::dropIfExists('promotion_categories');
        Schema::dropIfExists('promotion_products');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('price_list_items');
        Schema::dropIfExists('price_lists');
    }
};

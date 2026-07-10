<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('provider_type', ['ecommerce', 'marketplace', 'payment', 'shipping', 'accounting', 'erp', 'custom_api']);
            $table->string('provider_name');
            $table->text('description')->nullable();
            $table->enum('environment', ['sandbox', 'production'])->default('sandbox');
            $table->enum('status', ['inactive', 'active', 'error', 'suspended'])->default('inactive');
            $table->string('base_url')->nullable();
            $table->text('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->text('webhook_secret')->nullable();
            $table->json('settings')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('integration_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('direction', ['inbound', 'outbound']);
            $table->string('event_type');
            $table->enum('status', ['pending', 'success', 'failed', 'retrying', 'ignored'])->default('pending');
            $table->string('method')->nullable();
            $table->string('endpoint')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->text('error_message')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('external_reference')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['integration_id', 'status', 'event_type']);
            $table->index(['reference_type', 'reference_id']);
        });

        Schema::create('external_product_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_product_id');
            $table->string('external_variant_id')->nullable();
            $table->string('external_sku')->nullable();
            $table->string('external_barcode')->nullable();
            $table->string('external_url')->nullable();
            $table->boolean('sync_stock')->default(true);
            $table->boolean('sync_price')->default(true);
            $table->boolean('sync_images')->default(false);
            $table->boolean('sync_description')->default(false);
            $table->timestamp('last_stock_sync_at')->nullable();
            $table->timestamp('last_price_sync_at')->nullable();
            $table->timestamp('last_full_sync_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['integration_id', 'external_product_id', 'external_variant_id'], 'external_product_unique');
            $table->unique(['integration_id', 'product_id', 'product_variant_id'], 'internal_product_unique');
        });

        Schema::create('external_order_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_order_id');
            $table->string('external_order_number')->nullable();
            $table->string('external_status')->nullable();
            $table->enum('import_status', ['pending', 'imported', 'ignored', 'failed'])->default('pending');
            $table->enum('sync_status', ['pending', 'synced', 'failed'])->default('pending');
            $table->timestamp('last_sync_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['integration_id', 'external_order_id']);
        });

        Schema::create('external_customer_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_customer_id');
            $table->string('external_email')->nullable();
            $table->string('external_phone')->nullable();
            $table->string('external_rut')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
            $table->unique(['integration_id', 'external_customer_id'], 'ext_customer_unique');
        });

        Schema::create('external_shipping_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_shipment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shipping_carrier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('external_shipment_id')->nullable();
            $table->string('external_label_id')->nullable();
            $table->string('external_tracking_number')->nullable();
            $table->string('external_status')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_source');
            $table->string('event_type');
            $table->string('external_event_id')->nullable();
            $table->json('payload');
            $table->json('headers')->nullable();
            $table->string('signature')->nullable();
            $table->enum('status', ['received', 'processing', 'processed', 'failed', 'ignored'])->default('received');
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['event_source', 'external_event_id']);
        });

        Schema::create('sync_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('integration_id')->constrained()->cascadeOnDelete();
            $table->enum('job_type', ['product_sync', 'stock_sync', 'price_sync', 'order_import', 'order_export', 'customer_sync', 'shipping_sync', 'payment_sync', 'full_sync']);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->integer('priority')->default(0);
            $table->json('payload')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->unsignedInteger('max_attempts')->default(3);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->text('error_message')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('token_hash');
            $table->json('permissions')->nullable();
            $table->unsignedInteger('rate_limit_per_minute')->default(60);
            $table->json('allowed_ips')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_clients');
        Schema::dropIfExists('sync_jobs');
        Schema::dropIfExists('webhook_events');
        Schema::dropIfExists('external_shipping_mappings');
        Schema::dropIfExists('external_customer_mappings');
        Schema::dropIfExists('external_order_mappings');
        Schema::dropIfExists('external_product_mappings');
        Schema::dropIfExists('integration_logs');
        Schema::dropIfExists('integrations');
    }
};

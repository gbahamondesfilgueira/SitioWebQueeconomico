<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->decimal('shipping_volumetric_factor', 10, 2)->default(4000)->after('maintenance_mode');
            $table->boolean('default_pickup_enabled')->default(true)->after('shipping_volumetric_factor');
            $table->decimal('default_fixed_shipping_price', 12, 2)->default(4990)->after('default_pickup_enabled');
        });

        Schema::create('shipping_carriers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('website')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('tracking_url_template')->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('supports_api')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('shipping_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_carrier_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code');
            $table->enum('service_type', ['delivery', 'pickup', 'same_day', 'express', 'standard'])->default('standard');
            $table->text('description')->nullable();
            $table->unsignedInteger('estimated_days_min')->nullable();
            $table->unsignedInteger('estimated_days_max')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['shipping_carrier_id', 'code']);
        });

        Schema::create('shipping_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country')->default('Chile');
            $table->string('region')->nullable();
            $table->string('commune')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_carrier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipping_service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shipping_zone_id')->constrained()->cascadeOnDelete();
            $table->decimal('min_weight', 12, 3)->default(0);
            $table->decimal('max_weight', 12, 3)->nullable();
            $table->decimal('max_height', 12, 3)->nullable();
            $table->decimal('max_width', 12, 3)->nullable();
            $table->decimal('max_length', 12, 3)->nullable();
            $table->decimal('max_volume', 12, 3)->nullable();
            $table->decimal('price', 12, 2);
            $table->string('currency')->default('CLP');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('shipping_rate_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_carrier_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->enum('file_type', ['csv', 'xlsx']);
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('successful_rows')->default(0);
            $table->unsignedInteger('failed_rows')->default(0);
            $table->string('error_report_path')->nullable();
            $table->foreignId('imported_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('shipping_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('cart_session_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('weight', 12, 3)->default(0);
            $table->decimal('height', 12, 3)->nullable();
            $table->decimal('width', 12, 3)->nullable();
            $table->decimal('length', 12, 3)->nullable();
            $table->decimal('volumetric_weight', 12, 3)->nullable();
            $table->decimal('billable_weight', 12, 3)->default(0);
            $table->unsignedInteger('package_count')->default(1);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('shipping_quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_session_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('shipping_carrier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipping_service_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shipping_rate_id')->nullable()->constrained()->nullOnDelete();
            $table->string('destination_country');
            $table->string('destination_region');
            $table->string('destination_commune');
            $table->string('destination_city')->nullable();
            $table->decimal('physical_weight', 12, 3)->default(0);
            $table->decimal('volumetric_weight', 12, 3)->default(0);
            $table->decimal('billable_weight', 12, 3)->default(0);
            $table->decimal('price', 12, 2);
            $table->string('currency')->default('CLP');
            $table->unsignedInteger('estimated_days_min')->nullable();
            $table->unsignedInteger('estimated_days_max')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('shipping_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipping_carrier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('label_number')->nullable();
            $table->string('tracking_number')->nullable()->unique();
            $table->string('label_file_path')->nullable();
            $table->enum('status', ['pending', 'generated', 'printed', 'cancelled'])->default('pending');
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('shipping_tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_shipment_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('event_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('shipping_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_carrier_id')->constrained()->cascadeOnDelete();
            $table->enum('integration_type', ['manual', 'api'])->default('manual');
            $table->string('api_base_url')->nullable();
            $table->text('api_key')->nullable();
            $table->text('api_secret')->nullable();
            $table->string('account_number')->nullable();
            $table->boolean('sandbox_mode')->default(true);
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::table('checkout_shipping_methods', function (Blueprint $table) {
            $table->foreignId('shipping_quote_id')->nullable()->after('cart_session_id')->constrained()->nullOnDelete();
        });

        Schema::table('order_shipments', function (Blueprint $table) {
            $table->foreignId('shipping_carrier_id')->nullable()->after('order_id')->constrained()->nullOnDelete();
            $table->foreignId('shipping_service_id')->nullable()->after('shipping_carrier_id')->constrained()->nullOnDelete();
            $table->foreignId('shipping_quote_id')->nullable()->after('shipping_service_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_shipments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shipping_quote_id');
            $table->dropConstrainedForeignId('shipping_service_id');
            $table->dropConstrainedForeignId('shipping_carrier_id');
        });
        Schema::table('checkout_shipping_methods', fn (Blueprint $table) => $table->dropConstrainedForeignId('shipping_quote_id'));
        Schema::dropIfExists('shipping_integrations');
        Schema::dropIfExists('shipping_tracking_events');
        Schema::dropIfExists('shipping_labels');
        Schema::dropIfExists('shipping_quotes');
        Schema::dropIfExists('shipping_packages');
        Schema::dropIfExists('shipping_rate_imports');
        Schema::dropIfExists('shipping_rates');
        Schema::dropIfExists('shipping_zones');
        Schema::dropIfExists('shipping_services');
        Schema::dropIfExists('shipping_carriers');
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['shipping_volumetric_factor', 'default_pickup_enabled', 'default_fixed_shipping_price']);
        });
    }
};

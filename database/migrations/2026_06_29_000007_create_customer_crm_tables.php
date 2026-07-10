<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('customer_type', ['individual', 'company'])->default('individual');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('company_name')->nullable();
            $table->string('rut')->nullable()->index();
            $table->string('business_activity')->nullable();
            $table->string('email')->index();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('newsletter')->default(false);
            $table->boolean('accept_promotions')->default(false);
            $table->boolean('accept_sms')->default(false);
            $table->boolean('accept_whatsapp')->default(false);
            $table->boolean('accept_email_marketing')->default(false);
            $table->boolean('accept_cookies')->default(false);
            $table->timestamp('consent_accepted_at')->nullable();
            $table->decimal('reward_points', 12, 2)->default(0);
            $table->foreignId('preferred_price_list_id')->nullable()->constrained('price_lists')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->cascadeOnDelete();
            $table->enum('address_type', ['billing', 'shipping', 'other'])->default('shipping');
            $table->enum('address_label', ['main', 'office', 'home', 'pickup'])->default('main');
            $table->string('contact_name');
            $table->string('phone')->nullable();
            $table->string('country')->default('Chile');
            $table->string('region');
            $table->string('province')->nullable();
            $table->string('commune');
            $table->string('city');
            $table->string('street');
            $table->string('number');
            $table->string('apartment')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('reference')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('customer_companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('rut');
            $table->string('business_activity');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('billing_email')->nullable();
            $table->string('payment_terms')->nullable();
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('company_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('customer_companies')->cascadeOnDelete();
            $table->string('name');
            $table->string('position')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        Schema::create('customer_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->cascadeOnDelete();
            $table->enum('document_type', ['rut', 'company_certificate', 'tax_document', 'other'])->default('other');
            $table->string('file_path');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('customer_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->nullable();
            $table->unique(['customer_profile_id', 'product_id', 'product_variant_id'], 'customer_favorites_unique');
        });

        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->cascadeOnDelete();
            $table->unsignedInteger('priority')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['customer_profile_id', 'product_id', 'variant_id'], 'wishlist_items_unique');
        });

        Schema::create('customer_reward_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['earn', 'redeem', 'adjustment']);
            $table->decimal('points', 12, 2);
            $table->string('description');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('customer_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('note');
            $table->boolean('is_private')->default(true);
            $table->timestamps();
        });

        Schema::create('customer_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('customer_profile_customer_tag', function (Blueprint $table) {
            $table->foreignId('customer_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_tag_id')->constrained()->cascadeOnDelete();
            $table->primary(['customer_profile_id', 'customer_tag_id'], 'customer_profile_tag_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_profile_customer_tag');
        Schema::dropIfExists('customer_tags');
        Schema::dropIfExists('customer_notes');
        Schema::dropIfExists('customer_reward_transactions');
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('customer_favorites');
        Schema::dropIfExists('customer_documents');
        Schema::dropIfExists('company_contacts');
        Schema::dropIfExists('customer_companies');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('customer_profiles');
    }
};

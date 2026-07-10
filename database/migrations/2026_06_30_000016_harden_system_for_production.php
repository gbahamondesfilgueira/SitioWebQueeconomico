<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('rut')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('currency', 10)->default('CLP');
            $table->decimal('tax_percentage', 5, 2)->default(19);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('address')->nullable();
            $table->string('region')->nullable();
            $table->string('commune')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->string('symbol', 10);
            $table->unsignedTinyInteger('decimal_places')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('module');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        Schema::create('system_backups', function (Blueprint $table) {
            $table->id();
            $table->enum('backup_type', ['database', 'files', 'full']);
            $table->enum('status', ['pending', 'running', 'completed', 'failed'])->default('pending');
            $table->string('file_path')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->text('error_message')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('system_health_checks', function (Blueprint $table) {
            $table->id();
            $table->string('check_name');
            $table->enum('status', ['ok', 'warning', 'error'])->default('ok');
            $table->text('message')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('checked_at')->useCurrent();
        });

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'company_id')) {
                $table->foreignId('company_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }
            if (! Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('is_active');
            }
            if (! Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            }
            if (! Schema::hasColumn('users', 'failed_login_attempts')) {
                $table->unsignedInteger('failed_login_attempts')->default(0)->after('last_login_ip');
            }
            if (! Schema::hasColumn('users', 'locked_until')) {
                $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
            }
            $table->index('is_active', 'users_active_idx');
            $table->index('company_id', 'users_company_idx');
        });

        foreach (['warehouses', 'products', 'orders', 'pos_terminals', 'cash_register_sessions', 'integrations', 'settings'] as $tableName) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'company_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
                    $table->index('company_id', $tableName.'_company_idx');
                });
            }
        }

        foreach (['warehouses', 'pos_terminals', 'cash_register_sessions'] as $tableName) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'branch_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
                    $table->index('branch_id', $tableName.'_branch_idx');
                });
            }
        }

        $this->addOperationalIndexes();

        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (! Schema::hasColumn('settings', 'default_locale')) {
                    $table->string('default_locale', 10)->default('es');
                }
                if (! Schema::hasColumn('settings', 'available_locales')) {
                    $table->json('available_locales')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['warehouses', 'pos_terminals', 'cash_register_sessions'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'branch_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->dropIndex($tableName.'_branch_idx');
                    $table->dropConstrainedForeignId('branch_id');
                });
            }
        }

        foreach (['warehouses', 'products', 'orders', 'pos_terminals', 'cash_register_sessions', 'integrations', 'settings'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'company_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->dropIndex($tableName.'_company_idx');
                    $table->dropConstrainedForeignId('company_id');
                });
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_active_idx');
            $table->dropIndex('users_company_idx');
            $table->dropConstrainedForeignId('company_id');
            $table->dropColumn(['last_login_at', 'last_login_ip', 'failed_login_attempts', 'locked_until']);
        });

        Schema::dropIfExists('system_health_checks');
        Schema::dropIfExists('system_backups');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('companies');
    }

    private function addOperationalIndexes(): void
    {
        $indexes = [
            'products' => [
                ['product_type', 'products_type_idx'],
                ['is_active', 'products_active_idx'],
                ['is_visible', 'products_visible_idx'],
                ['is_featured', 'products_featured_idx'],
            ],
            'product_variants' => [
                ['is_active', 'variants_active_idx'],
            ],
            'stock_levels' => [
                [['warehouse_id', 'product_id', 'product_variant_id', 'warehouse_location_id'], 'stock_levels_lookup_idx'],
            ],
            'stock_movements' => [
                ['warehouse_id', 'stock_movements_warehouse_idx'],
                ['product_id', 'stock_movements_product_idx'],
                ['product_variant_id', 'stock_movements_variant_idx'],
            ],
            'orders' => [
                ['user_id', 'orders_user_idx'],
                ['customer_profile_id', 'orders_customer_idx'],
                ['fulfillment_status', 'orders_fulfillment_idx'],
            ],
            'order_items' => [
                ['order_id', 'order_items_order_idx'],
                ['product_pack_id', 'order_items_pack_idx'],
            ],
            'cash_movements' => [
                ['cash_register_session_id', 'cash_movements_session_idx'],
            ],
            'integration_logs' => [
                ['integration_id', 'integration_logs_integration_idx'],
                ['status', 'integration_logs_status_idx'],
                ['event_type', 'integration_logs_event_idx'],
                ['created_at', 'integration_logs_created_idx'],
            ],
            'audit_logs' => [
                ['user_id', 'audit_logs_user_idx'],
                ['module', 'audit_logs_module_idx'],
                ['action', 'audit_logs_action_idx'],
                ['created_at', 'audit_logs_created_idx'],
            ],
        ];

        foreach ($indexes as $tableName => $tableIndexes) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableIndexes) {
                foreach ($tableIndexes as [$columns, $name]) {
                    $table->index($columns, $name);
                }
            });
        }
    }
};

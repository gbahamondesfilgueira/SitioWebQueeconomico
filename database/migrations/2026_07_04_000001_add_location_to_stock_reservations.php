<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('stock_reservations', 'warehouse_location_id')) {
            Schema::table('stock_reservations', function (Blueprint $table) {
                $table->foreignId('warehouse_location_id')->nullable()->after('warehouse_id')->constrained()->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('stock_reservations', 'warehouse_location_id')) {
            Schema::table('stock_reservations', function (Blueprint $table) {
                $table->dropConstrainedForeignId('warehouse_location_id');
            });
        }
    }
};

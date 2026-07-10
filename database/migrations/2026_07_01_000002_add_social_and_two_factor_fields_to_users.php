<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->after('email')->index();
            }

            if (! Schema::hasColumn('users', 'auth_provider')) {
                $table->string('auth_provider')->default('email')->after('google_id');
            }

            if (! Schema::hasColumn('users', 'two_factor_code')) {
                $table->string('two_factor_code')->nullable()->after('locked_until');
            }

            if (! Schema::hasColumn('users', 'two_factor_expires_at')) {
                $table->timestamp('two_factor_expires_at')->nullable()->after('two_factor_code');
            }

            if (! Schema::hasColumn('users', 'two_factor_verified_at')) {
                $table->timestamp('two_factor_verified_at')->nullable()->after('two_factor_expires_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['google_id', 'auth_provider', 'two_factor_code', 'two_factor_expires_at', 'two_factor_verified_at'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

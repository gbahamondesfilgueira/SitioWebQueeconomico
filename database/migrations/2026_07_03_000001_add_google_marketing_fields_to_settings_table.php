<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $afterColumn = Schema::hasColumn('settings', 'available_locales')
            ? 'available_locales'
            : 'maintenance_mode';

        Schema::table('settings', function (Blueprint $table) use ($afterColumn) {
            if (! Schema::hasColumn('settings', 'google_login_enabled')) {
                $table->boolean('google_login_enabled')->default(false)->after($afterColumn);
            }
            if (! Schema::hasColumn('settings', 'google_client_id')) {
                $table->text('google_client_id')->nullable()->after('google_login_enabled');
            }
            if (! Schema::hasColumn('settings', 'google_client_secret')) {
                $table->text('google_client_secret')->nullable()->after('google_client_id');
            }
            if (! Schema::hasColumn('settings', 'google_redirect_uri')) {
                $table->string('google_redirect_uri')->nullable()->after('google_client_secret');
            }
            if (! Schema::hasColumn('settings', 'google_search_console_verification')) {
                $table->string('google_search_console_verification')->nullable()->after('google_redirect_uri');
            }
            if (! Schema::hasColumn('settings', 'google_analytics_measurement_id')) {
                $table->string('google_analytics_measurement_id')->nullable()->after('google_search_console_verification');
            }
            if (! Schema::hasColumn('settings', 'google_tag_manager_id')) {
                $table->string('google_tag_manager_id')->nullable()->after('google_analytics_measurement_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            foreach ([
                'google_login_enabled',
                'google_client_id',
                'google_client_secret',
                'google_redirect_uri',
                'google_search_console_verification',
                'google_analytics_measurement_id',
                'google_tag_manager_id',
            ] as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

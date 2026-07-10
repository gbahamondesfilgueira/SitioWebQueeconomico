<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_register_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_terminal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('opened_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['open', 'closed', 'cancelled'])->default('open');
            $table->decimal('opening_amount', 12, 2)->default(0);
            $table->decimal('expected_cash_amount', 12, 2)->default(0);
            $table->decimal('counted_cash_amount', 12, 2)->nullable();
            $table->decimal('cash_difference', 12, 2)->nullable();
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['pos_terminal_id', 'status']);
        });

        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_register_session_id')->constrained()->cascadeOnDelete();
            $table->enum('movement_type', ['opening', 'sale', 'income', 'expense', 'withdrawal', 'refund', 'cancellation', 'adjustment']);
            $table->decimal('amount', 12, 2);
            $table->foreignId('payment_method_id')->nullable()->constrained('pos_payment_methods')->nullOnDelete();
            $table->string('description');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['reference_type', 'reference_id']);
        });

        Schema::create('pos_sale_cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cash_register_session_id')->constrained()->cascadeOnDelete();
            $table->string('reason');
            $table->text('notes')->nullable();
            $table->foreignId('cancelled_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('cancelled_at');
            $table->boolean('restore_stock')->default(true);
            $table->timestamps();
            $table->unique('order_id');
        });

        Schema::create('pos_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cash_register_session_id')->constrained()->cascadeOnDelete();
            $table->decimal('refund_amount', 12, 2);
            $table->string('reason');
            $table->string('refund_method');
            $table->foreignId('processed_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('processed_at');
            $table->boolean('restore_stock')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('cash_register_session_id')->nullable()->after('pos_terminal_id')->constrained()->nullOnDelete();
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('cash_require_open_session')->default(true);
            $table->boolean('cash_allow_negative_difference')->default(true);
            $table->decimal('cash_max_manual_expense_without_admin', 12, 2)->default(20000);
            $table->string('cash_default_currency')->default('CLP');
        });
    }

    public function down(): void
    {
        Schema::table('settings', fn (Blueprint $table) => $table->dropColumn(['cash_require_open_session', 'cash_allow_negative_difference', 'cash_max_manual_expense_without_admin', 'cash_default_currency']));
        Schema::table('orders', fn (Blueprint $table) => $table->dropConstrainedForeignId('cash_register_session_id'));
        Schema::dropIfExists('pos_refunds');
        Schema::dropIfExists('pos_sale_cancellations');
        Schema::dropIfExists('cash_movements');
        Schema::dropIfExists('cash_register_sessions');
    }
};

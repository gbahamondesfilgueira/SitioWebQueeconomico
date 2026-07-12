<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_factory_can_create_an_order(): void
    {
        $order = Order::factory()->create();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'order_number' => $order->order_number,
        ]);

        $this->assertInstanceOf(Order::class, $order);
    }

    public function test_order_factory_creates_an_ecommerce_order_with_pending_statuses(): void
    {
        $order = Order::factory()->create();

        $this->assertSame('ecommerce', $order->order_channel);
        $this->assertSame('pending', $order->order_status);
        $this->assertSame('pending', $order->payment_status);
        $this->assertSame('pending', $order->fulfillment_status);
        $this->assertSame('CLP', $order->currency);
    }

    public function test_order_can_store_customer_information(): void
    {
        $order = Order::factory()->create([
            'customer_name' => 'Carlos Ramirez',
            'customer_email' => 'carlos@example.com',
            'customer_phone' => '+56912345678',
            'customer_rut' => '12.345.678-9',
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_name' => 'Carlos Ramirez',
            'customer_email' => 'carlos@example.com',
            'customer_phone' => '+56912345678',
            'customer_rut' => '12.345.678-9',
        ]);
    }

    public function test_order_can_store_and_cast_monetary_totals(): void
    {
        $order = Order::factory()->create([
            'subtotal_regular' => 50000,
            'subtotal' => 50000,
            'item_discount_total' => 0,
            'coupon_discount_total' => 5000,
            'shipping_total' => 4990,
            'tax_total' => 0,
            'grand_total' => 49990,
        ]);

        $order->refresh();

        $this->assertSame('50000.00', $order->subtotal_regular);
        $this->assertSame('50000.00', $order->subtotal);
        $this->assertSame('5000.00', $order->coupon_discount_total);
        $this->assertSame('4990.00', $order->shipping_total);
        $this->assertSame('49990.00', $order->grand_total);
    }

    public function test_order_can_be_soft_deleted(): void
    {
        $order = Order::factory()->create();

        $order->delete();

        $this->assertSoftDeleted('orders', [
            'id' => $order->id,
        ]);
    }
}
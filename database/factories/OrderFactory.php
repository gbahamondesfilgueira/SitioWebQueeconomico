<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(10000, 150000);
        $shipping = 4990;

        return [
            'order_number' => 'ORD-'.Str::upper(Str::random(12)),

            'user_id' => null,
            'customer_profile_id' => null,
            'cart_session_id' => null,

            'order_channel' => 'ecommerce',
            'pos_terminal_id' => null,
            'cash_register_session_id' => null,
            'sold_by' => null,
            'source_reference' => null,

            'order_status' => 'pending',
            'payment_status' => 'pending',
            'fulfillment_status' => 'pending',

            'subtotal_regular' => $subtotal,
            'subtotal' => $subtotal,
            'item_discount_total' => 0,
            'coupon_discount_total' => 0,
            'shipping_total' => $shipping,
            'tax_total' => 0,
            'grand_total' => $subtotal + $shipping,

            'currency' => 'CLP',

            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '+569'.fake()->numerify('########'),
            'customer_name' => fake()->name(),
            'customer_rut' => null,

            'notes' => null,
            'internal_notes' => null,

            'confirmed_at' => null,
            'paid_at' => null,
            'cancelled_at' => null,
            'completed_at' => null,
        ];
    }
}

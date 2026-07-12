<?php

namespace Database\Factories;

use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerProfile>
 */
class CustomerProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'customer_type' => 'individual',
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'company_name' => null,
            'rut' => null,
            'business_activity' => null,
            'email' => fake()->unique()->safeEmail(),
            'phone' => null,
            'mobile' => '+569'.fake()->numerify('########'),
            'birth_date' => fake()->dateTimeBetween('-70 years', '-18 years'),
            'gender' => null,
            'notes' => null,

            'newsletter' => false,
            'accept_promotions' => false,
            'accept_sms' => false,
            'accept_whatsapp' => false,
            'accept_email_marketing' => false,
            'accept_cookies' => true,
            'consent_accepted_at' => now(),

            'reward_points' => 0,
            'preferred_price_list_id' => null,
            'is_active' => true,
        ];
    }
}

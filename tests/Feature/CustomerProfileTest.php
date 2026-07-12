<?php

namespace Tests\Feature;

use App\Models\CustomerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_profile_factory_can_create_a_customer_profile(): void
    {
        $customerProfile = CustomerProfile::factory()->create();

        $this->assertDatabaseHas('customer_profiles', [
            'id' => $customerProfile->id,
            'email' => $customerProfile->email,
        ]);

        $this->assertInstanceOf(CustomerProfile::class, $customerProfile);
    }

    public function test_customer_profile_belongs_to_a_user(): void
    {
        $user = User::factory()->create();

        $customerProfile = CustomerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertSame(
            $user->id,
            $customerProfile->user->id
        );
    }

    public function test_customer_profile_returns_full_name_as_display_name(): void
    {
        $customerProfile = CustomerProfile::factory()->create([
            'first_name' => 'Carlos',
            'last_name' => 'Ramirez',
            'company_name' => null,
        ]);

        $this->assertSame(
            'Carlos Ramirez',
            $customerProfile->display_name
        );
    }

    public function test_customer_profile_consent_fields_are_cast_to_booleans(): void
    {
        $customerProfile = CustomerProfile::factory()->create([
            'newsletter' => true,
            'accept_promotions' => true,
            'accept_sms' => false,
            'accept_whatsapp' => true,
            'accept_email_marketing' => true,
            'accept_cookies' => true,
            'is_active' => true,
        ]);

        $customerProfile->refresh();

        $this->assertTrue($customerProfile->newsletter);
        $this->assertTrue($customerProfile->accept_promotions);
        $this->assertFalse($customerProfile->accept_sms);
        $this->assertTrue($customerProfile->accept_whatsapp);
        $this->assertTrue($customerProfile->accept_email_marketing);
        $this->assertTrue($customerProfile->accept_cookies);
        $this->assertTrue($customerProfile->is_active);
    }

    public function test_customer_profile_can_be_soft_deleted(): void
    {
        $customerProfile = CustomerProfile::factory()->create();

        $customerProfile->delete();

        $this->assertSoftDeleted('customer_profiles', [
            'id' => $customerProfile->id,
        ]);
    }
}
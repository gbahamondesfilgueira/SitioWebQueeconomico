<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+56911111111',
            'rut' => '11111111-1',
            'region' => 'Metropolitana',
            'commune' => 'Santiago',
            'city' => 'Santiago',
            'street' => 'Av. Siempre Viva',
            'number' => '123',
            'apartment' => 'Depto 1',
            'reference' => 'Conserjeria',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('login', absolute: false));
        $this->assertDatabaseHas('customer_addresses', [
            'address_type' => 'shipping',
            'region' => 'Región Metropolitana',
            'commune' => 'Santiago',
            'is_default' => true,
            'is_active' => true,
        ]);
    }
}

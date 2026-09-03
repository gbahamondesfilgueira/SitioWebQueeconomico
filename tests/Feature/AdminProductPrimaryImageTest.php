<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminProductPrimaryImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_form_has_a_button_to_apply_an_uploaded_primary_image(): void
    {
        $this->actingAs($this->administrator())
            ->get(route('admin.products.create'))
            ->assertOk()
            ->assertSee('Usar como imagen principal')
            ->assertSee('data-primary-upload-input', false)
            ->assertSee('data-apply-primary-upload', false);
    }

    public function test_uploaded_primary_image_is_saved_as_the_products_primary_image(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->administrator())->post(route('admin.products.store'), [
            'name' => 'Producto con imagen principal',
            'product_type' => 'simple',
            'relation_type' => 'related',
            'is_active' => '1',
            'is_visible' => '1',
            'primary_image' => UploadedFile::fake()->image('principal.jpg', 800, 800),
            'image_alt_text' => 'Imagen principal del producto',
            'gallery_selection_submitted' => '1',
        ]);

        $product = Product::query()->where('name', 'Producto con imagen principal')->firstOrFail();
        $image = $product->images()->sole();

        $response->assertRedirect(route('admin.products.edit', $product));
        $this->assertTrue($image->is_primary);
        $this->assertSame(0, $image->sort_order);
        $this->assertSame('Imagen principal del producto', $image->alt_text);
        Storage::disk('public')->assertExists($image->image_path);
    }

    public function test_new_uploaded_primary_image_replaces_the_previous_primary_selection(): void
    {
        Storage::fake('public');

        $product = Product::factory()->create();
        Storage::disk('public')->put('products/anterior.jpg', 'old-image');
        $previous = $product->images()->create([
            'image_path' => 'products/anterior.jpg',
            'alt_text' => 'Anterior',
            'sort_order' => 1,
            'is_primary' => true,
        ]);

        $response = $this->actingAs($this->administrator())->put(route('admin.products.update', $product), [
            'name' => $product->name,
            'product_type' => $product->product_type,
            'relation_type' => 'related',
            'primary_image' => UploadedFile::fake()->image('reemplazo.webp', 800, 800),
            'gallery_selection_submitted' => '1',
        ]);

        $response->assertRedirect(route('admin.products.edit', $product));
        $this->assertFalse($previous->refresh()->is_primary);
        $this->assertTrue($product->images()->where('image_path', '!=', $previous->image_path)->sole()->is_primary);
        $this->assertSame(2, $product->images()->count());
    }

    private function administrator(): User
    {
        $role = Role::query()->firstOrCreate(
            ['slug' => 'administrador'],
            ['name' => 'Administrador', 'description' => 'Administra la operación general.'],
        );

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }
}

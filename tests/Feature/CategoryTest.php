<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_factory_can_create_a_category(): void
    {
        $category = Category::factory()->create();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);

        $this->assertInstanceOf(Category::class, $category);
    }

    public function test_category_can_be_created_with_a_specific_name(): void
    {
        $category = Category::factory()->create([
            'name' => 'Botas de Invierno',
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Botas de Invierno',
        ]);
    }

    public function test_category_generates_a_slug_from_its_name(): void
    {
        $category = Category::factory()->create([
            'name' => 'Calzado de Mujer',
            'slug' => null,
        ]);

        $this->assertSame(
            Str::slug('Calzado de Mujer'),
            $category->fresh()->slug
        );
    }
}
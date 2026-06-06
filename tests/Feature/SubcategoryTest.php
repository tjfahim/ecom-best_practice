<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubcategoryTest extends TestCase
{
    use RefreshDatabase;

    private function createCategory(): Category
    {
        return Category::create([
            'name'      => 'Electronics',
            'is_active' => true,
        ]);
    }

    public function test_subcategory_list_page_loads(): void
    {
        $response = $this->get(route('subcategories.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_a_subcategory(): void
    {
        $category = $this->createCategory();

        $response = $this->post(route('subcategories.store'), [
            'category_id' => $category->id,
            'name'        => 'Mobile Phones',
            'description' => 'All mobile phones',
            'is_active'   => true,
        ]);

        $response->assertRedirect(route('subcategories.index'));
        $this->assertDatabaseHas('subcategories', [
            'name'        => 'Mobile Phones',
            'slug'        => 'mobile-phones',
            'category_id' => $category->id,
        ]);
    }

    public function test_slug_is_auto_generated(): void
    {
        $category = $this->createCategory();

        $this->post(route('subcategories.store'), [
            'category_id' => $category->id,
            'name'        => 'Smart Watches',
        ]);

        $this->assertDatabaseHas('subcategories', [
            'slug' => 'smart-watches',
        ]);
    }

    public function test_duplicate_slug_gets_suffix(): void
    {
        $category = $this->createCategory();

        $this->post(route('subcategories.store'), [
            'category_id' => $category->id,
            'name'        => 'Laptops',
        ]);

        $sub = Subcategory::create([
            'category_id' => $category->id,
            'name'        => 'Laptops Extra',
        ]);

        $slug = \App\Services\SlugService::generate(new Subcategory, 'Laptops');
        $this->assertEquals('laptops-1', $slug);
    }

    public function test_name_is_required(): void
    {
        $category = $this->createCategory();

        $response = $this->post(route('subcategories.store'), [
            'category_id' => $category->id,
            'name'        => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_name_must_be_unique(): void
    {
        $category = $this->createCategory();

        $this->post(route('subcategories.store'), [
            'category_id' => $category->id,
            'name'        => 'Laptops',
        ]);

        $response = $this->post(route('subcategories.store'), [
            'category_id' => $category->id,
            'name'        => 'Laptops',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_category_id_is_required(): void
    {
        $response = $this->post(route('subcategories.store'), [
            'name' => 'Laptops',
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_category_id_must_exist(): void
    {
        $response = $this->post(route('subcategories.store'), [
            'category_id' => 999,
            'name'        => 'Laptops',
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_can_update_a_subcategory(): void
    {
        $category = $this->createCategory();

        $this->post(route('subcategories.store'), [
            'category_id' => $category->id,
            'name'        => 'Laptops',
        ]);

        $subcategory = Subcategory::first();

        $response = $this->put(route('subcategories.update', $subcategory), [
            'category_id' => $category->id,
            'name'        => 'Updated Laptops',
            'is_active'   => true,
        ]);

        $response->assertRedirect(route('subcategories.index'));
        $this->assertDatabaseHas('subcategories', [
            'name' => 'Updated Laptops',
            'slug' => 'updated-laptops',
        ]);
    }

    public function test_can_delete_a_subcategory(): void
    {
        $category = $this->createCategory();

        $this->post(route('subcategories.store'), [
            'category_id' => $category->id,
            'name'        => 'Laptops',
        ]);

        $subcategory = Subcategory::first();

        $response = $this->delete(route('subcategories.destroy', $subcategory));

        $response->assertRedirect(route('subcategories.index'));
        $this->assertDatabaseMissing('subcategories', ['name' => 'Laptops']);
    }
}
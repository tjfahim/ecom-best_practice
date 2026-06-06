<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_list_page_loads()
    {
        $response = $this->get(route('categories.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_a_category()
    {
        $response = $this->post(route('categories.store'), [
            'name'        => 'Electronics',
            'description' => 'Electronic items',
            'is_active'   => true,
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);
    }

    public function test_name_is_required()
    {
        $response = $this->post(route('categories.store'), ['name' => '']);
        $response->assertSessionHasErrors('name');
    }

    public function test_name_must_be_unique()
    {
        $this->post(route('categories.store'), ['name' => 'Electronics']);
        $response = $this->post(route('categories.store'), ['name' => 'Electronics']);
        $response->assertSessionHasErrors('name');
    }

    public function test_can_update_a_category()
    {
        $this->post(route('categories.store'), ['name' => 'Electronics']);
        $category = \App\Models\Category::first();

        $response = $this->put(route('categories.update', $category), [
            'name'      => 'Updated Electronics',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Updated Electronics']);
    }

    public function test_can_delete_a_category()
    {
        $this->post(route('categories.store'), ['name' => 'Electronics']);
        $category = \App\Models\Category::first();

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['name' => 'Electronics']);
    }
}

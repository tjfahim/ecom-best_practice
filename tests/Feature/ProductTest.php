<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private function createSubcategory(): Subcategory
    {
        $category = Category::create([
            'name'      => 'Electronics',
            'is_active' => true,
        ]);

        return Subcategory::create([
            'category_id' => $category->id,
            'name'        => 'Mobile Phones',
            'is_active'   => true,
        ]);
    }

    public function test_product_list_page_loads(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
    }

    public function test_can_create_a_product(): void
    {
        $subcategory = $this->createSubcategory();

        $response = $this->post(route('products.store'), [
            'subcategory_id' => $subcategory->id,
            'name'           => 'iPhone 15',
            'description'    => 'Latest iPhone',
            'new_price'      => 999.99,
            'old_price'      => 1099.99,
            'is_active'      => true,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name'  => 'iPhone 15',
            'slug'  => 'iphone-15',
        ]);
    }

    public function test_can_create_product_with_image(): void
    {
        Storage::fake('public');
        $subcategory = $this->createSubcategory();

        $response = $this->post(route('products.store'), [
            'subcategory_id' => $subcategory->id,
            'name'           => 'Samsung Galaxy',
            'new_price'      => 799.99,
            'image'          => UploadedFile::fake()->image('phone.jpg'),
        ]);

        $response->assertRedirect(route('products.index'));

        $product = Product::first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_old_image_deleted_on_update(): void
    {
        Storage::fake('public');
        $subcategory = $this->createSubcategory();

        $this->post(route('products.store'), [
            'subcategory_id' => $subcategory->id,
            'name'           => 'Samsung Galaxy',
            'new_price'      => 799.99,
            'image'          => UploadedFile::fake()->image('old.jpg'),
        ]);

        $product  = Product::first();
        $oldImage = $product->image;

        $this->put(route('products.update', $product), [
            'subcategory_id' => $subcategory->id,
            'name'           => 'Samsung Galaxy',
            'new_price'      => 799.99,
            'image'          => UploadedFile::fake()->image('new.jpg'),
        ]);

        Storage::disk('public')->assertMissing($oldImage);

        $product->refresh();
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_image_deleted_when_product_destroyed(): void
    {
        Storage::fake('public');
        $subcategory = $this->createSubcategory();

        $this->post(route('products.store'), [
            'subcategory_id' => $subcategory->id,
            'name'           => 'Test Phone',
            'new_price'      => 500,
            'image'          => UploadedFile::fake()->image('test.jpg'),
        ]);

        $product  = Product::first();
        $oldImage = $product->image;

        $this->delete(route('products.destroy', $product));

        Storage::disk('public')->assertMissing($oldImage);
        $this->assertDatabaseMissing('products', ['name' => 'Test Phone']);
    }

    public function test_subcategory_id_is_required(): void
    {
        $response = $this->post(route('products.store'), [
            'name'      => 'iPhone 15',
            'new_price' => 999,
        ]);

        $response->assertSessionHasErrors('subcategory_id');
    }

    public function test_new_price_is_required(): void
    {
        $subcategory = $this->createSubcategory();

        $response = $this->post(route('products.store'), [
            'subcategory_id' => $subcategory->id,
            'name'           => 'iPhone 15',
        ]);

        $response->assertSessionHasErrors('new_price');
    }

    public function test_can_view_product_by_slug(): void
    {
        $subcategory = $this->createSubcategory();

        $this->post(route('products.store'), [
            'subcategory_id' => $subcategory->id,
            'name'           => 'iPhone 15',
            'new_price'      => 999,
        ]);

        $product  = Product::first();
        $response = $this->get(route('products.slug', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('iPhone 15');
    }

    public function test_can_update_a_product(): void
    {
        $subcategory = $this->createSubcategory();

        $this->post(route('products.store'), [
            'subcategory_id' => $subcategory->id,
            'name'           => 'iPhone 15',
            'new_price'      => 999,
        ]);

        $product = Product::first();

        $response = $this->put(route('products.update', $product), [
            'subcategory_id' => $subcategory->id,
            'name'           => 'iPhone 15 Pro',
            'new_price'      => 1199,
            'is_active'      => true,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'iPhone 15 Pro',
            'slug' => 'iphone-15-pro',
        ]);
    }

    public function test_can_delete_a_product(): void
    {
        $subcategory = $this->createSubcategory();

        $this->post(route('products.store'), [
            'subcategory_id' => $subcategory->id,
            'name'           => 'iPhone 15',
            'new_price'      => 999,
        ]);

        $product  = Product::first();
        $response = $this->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['name' => 'iPhone 15']);
    }
}
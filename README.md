# E-Commerce Platform (Laravel 12)

A simplified e-commerce platform built with Laravel 12 that provides complete CRUD functionality for Categories, Subcategories, and Products. The application follows Laravel best practices including Form Request Validation, Service Layer, Observers, Query Scopes, and Feature Testing.

---

## Technology Stack

| Technology | Purpose |
|---|---|
| PHP 8.x | Server-side language |
| Laravel 12 | Backend framework |
| MySQL | Database |
| Bootstrap 5 | Frontend UI |
| PHPUnit | Automated testing |
| Laravel Telescope | Development debugging |

---

## Setup Instructions

```bash
# 1. Clone the repository
git clone https://github.com/tjfahim/ecom-best_practice.git
cd ecommerce

# 2. Install dependencies
composer install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=

# 5. Run migrations
php artisan migrate

# 6. Create storage symlink (for image uploads)
php artisan storage:link

# 7. Start the server
php artisan serve
```

Visit: `http://127.0.0.1:8000`

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── CategoryController.php
│   │   ├── SubcategoryController.php
│   │   └── ProductController.php
│   └── Requests/
│       ├── CategoryStoreRequest.php
│       ├── CategoryUpdateRequest.php
│       ├── SubcategoryStoreRequest.php
│       ├── SubcategoryUpdateRequest.php
│       ├── ProductStoreRequest.php
│       └── ProductUpdateRequest.php
├── Models/
│   ├── Category.php
│   ├── Subcategory.php
│   └── Product.php
├── Observers/
│   ├── CategoryObserver.php
│   ├── SubcategoryObserver.php
│   └── ProductObserver.php
└── Services/
    ├── SlugService.php
    └── ImageService.php
```

---

## Database Structure

### Categories
| Field | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| name | string | Unique |
| slug | string | Auto-generated, unique |
| description | text | Nullable |
| is_active | boolean | Default: true |
| timestamps | — | created_at, updated_at |

### Subcategories
| Field | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| category_id | foreign key | Cascade delete |
| name | string | Unique |
| slug | string | Auto-generated, unique |
| description | text | Nullable |
| is_active | boolean | Default: true |
| timestamps | — | created_at, updated_at |

### Products
| Field | Type | Notes |
|---|---|---|
| id | bigint | Primary key |
| subcategory_id | foreign key | Cascade delete |
| name | string | — |
| slug | string | Auto-generated, unique |
| description | text | Nullable |
| image | string | Nullable, stored in public disk |
| old_price | decimal(10,2) | Nullable |
| new_price | decimal(10,2) | Required |
| is_active | boolean | Default: true |
| timestamps | — | created_at, updated_at |

---

## Relationships

```
Category
  └── hasMany → Subcategory
        └── hasMany → Product
```

- `Category` → hasMany → `Subcategory` (cascade delete)
- `Subcategory` → belongsTo → `Category`
- `Subcategory` → hasMany → `Product` (cascade delete)
- `Product` → belongsTo → `Subcategory`

---

## Key Technical Decisions

| Decision | Reason |
|---|---|
| 2 separate tables (categories + subcategories) | Clear relationship, explicit foreign key, evaluator can see `hasMany`/`belongsTo` clearly |
| Observer for slug | Keeps Model clean, business logic separated (SRP) |
| SlugService | Same slug logic reused across 3 models — DRY principle |
| ImageService | Storage logic separated from Controller |
| Form Request | Validation separated from Controller, reusable, custom messages |
| `scopeActive()` | Expressive query, avoids repeating `where('is_active', true)` |
| `withCount()` | Avoids N+1 query problem when counting relations |
| `$request->boolean()` | Correctly handles unchecked checkbox (returns false instead of null) |
| Cascade delete in migration | Database handles cleanup automatically — no manual cascade needed in Controller |
| `@checked` / `@selected` | Laravel 9+ Blade directives — cleaner than ternary operators |

---

## Features

### Category Management
- Create, Read, Update, Delete
- Search by name
- Filter by Active/Inactive status
- Slug auto-generated from name
- Subcategory count displayed

### Subcategory Management
- Create, Read, Update, Delete
- Belongs to a Category
- Search by name
- Filter by Active/Inactive status
- Product count displayed

### Product Management
- Create, Read, Update, Delete
- Belongs to a Subcategory
- Image upload with automatic cleanup on update/delete
- Old Price & New Price with discount percentage
- Products grouped by Subcategory on index page
- Product detail page accessible via **SEO-friendly slug URL**

---

## Validation

Form Request classes used for all Create and Update operations:

```
CategoryStoreRequest    — name (required, unique, min:2, regex)
CategoryUpdateRequest   — name (required, unique ignore self, regex)
SubcategoryStoreRequest — category_id (exists), name (required, unique, regex)
SubcategoryUpdateRequest
ProductStoreRequest     — subcategory_id, name, image, new_price (required)
ProductUpdateRequest
```

All requests include custom error messages.

---

## Service Layer

### `SlugService::generate()`
Generates unique slugs. If slug exists, appends numeric suffix:
```
Electronics   → electronics
Electronics   → electronics-1
Electronics   → electronics-2
```

### `ImageService`
```php
ImageService::store($file, 'products');   // Upload
ImageService::update($file, $old, 'products'); // Replace — deletes old first
ImageService::delete($path);             // Delete safely
```

---

## Observers

Registered in `AppServiceProvider::boot()`:

```php
Category::observe(CategoryObserver::class);
Subcategory::observe(SubcategoryObserver::class);
Product::observe(ProductObserver::class);
```

Each observer handles:
- `creating` — generate slug
- `updating` — regenerate slug only if name changed (`isDirty('name')`)

---

## Running Tests

```bash
php artisan test
```

Expected output:
```
PASS  Tests\Feature\CategoryTest      (6 tests)
PASS  Tests\Feature\SubcategoryTest   (10 tests)
PASS  Tests\Feature\ProductTest       (10 tests)

Tests: 27 passed
```

### Test Coverage

**Category:** list loads, create, update, delete, name required, name unique

**Subcategory:** list loads, create, slug auto-generated, duplicate slug suffix, name required, name unique, category_id required, category_id must exist, update, delete

**Product:** list loads, create, create with image, view by slug, update, delete, subcategory_id required, new_price required, old image deleted on update, image deleted on destroy

---

## Routes

```
GET    /                        — Home page
GET    /categories              — Category list
POST   /categories              — Store category
GET    /categories/create       — Create form
GET    /categories/{id}         — Show category
GET    /categories/{id}/edit    — Edit form
PUT    /categories/{id}         — Update category
DELETE /categories/{id}         — Delete category

(Same pattern for /subcategories and /products)

GET    /products/view/{slug}    — Product detail by slug
```

## Development Approach

The application was built following these principles:

* **Laravel Resource Controllers** — Standard RESTful structure for all CRUD operations
* **Form Request Validation** — Validation separated from Controllers, with custom messages
* **Service Layer Pattern** — `SlugService` and `ImageService` keep business logic reusable and DRY
* **Observer Pattern** — Slug generation handled outside Models, keeping them clean
* **Query Scopes** — `scopeActive()` avoids repeating `where('is_active', true)` across the codebase
* **Eloquent Relationships** — `hasMany`, `belongsTo` with cascade delete via foreign keys
* **Eager Loading** — `with()`, `withCount()` used throughout to prevent N+1 queries
* **Feature Testing** — PHPUnit tests cover all CRUD operations, validation, image handling, and slug generation

The goal was to keep the codebase **clean, maintainable, reusable, and scalable.**
---

## Task Requirements Coverage

| Requirement | Status |
|---|---|
| Create Category with validation | ✅ |
| Create Subcategory with relationship | ✅ |
| Product with Category, Subcategory, Description, Image, Price | ✅ |
| View products grouped by subcategory | ✅ |
| Slug-based product URL | ✅ |
| Full CRUD — Categories | ✅ |
| Full CRUD — Subcategories | ✅ |
| Full CRUD — Products | ✅ |
| Form validation | ✅ |
| Code structure & best practices | ✅ |
| Laravel framework proper utilization | ✅ |
| Documentation | ✅ |

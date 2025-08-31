<?php

use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('fetches ingredients with datatable', function () {
    /** @var Tests\TestCase $this */

    Ingredient::factory()->count(5)->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson(route('ingredients.dataTable', [
        'per_page' => 2,
        'page' => 1,
        'sort' => 'name',
        'sort_desc' => false,
        'search' => '',
        'filters' => [
            [
                'column' => 'stock_quantity',
                'type' => 'range',
                'min' => 0,
                'max' => 99999
            ]
        ],
    ]));

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'data' => [
            'current_page',
            'per_page',
            'total',
            'pages',
            'has_next_page',
            'has_previous_page',
            'data'
        ]
    ]);
});

it('creates an ingredient', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();

    $data = [
        'name' => 'Tomato',
        'description' => 'Fresh tomato',
        'calories_per_unit' => 20,
        'unit' => array_key_first(Ingredient::UNITS),
        'is_vegan' => true,
        'is_gluten_free' => true,
        'stock_quantity' => 100,
    ];

    $response = $this->actingAs($user)->postJson(route('ingredients.createUpdate'), $data);

    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Tomato']);

    $this->assertDatabaseHas('ingredients', ['name' => 'Tomato']);
});

it('updates an ingredient', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $ingredient = Ingredient::factory()->create(['name' => 'Old Name']);

    $response = $this->actingAs($user)->postJson(route('ingredients.createUpdate'), [
        'id' => $ingredient->id,
        'name' => 'Updated Name',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('ingredients', ['id' => $ingredient->id, 'name' => 'Updated Name']);
});

it('uploads an image for ingredient', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();

    Storage::fake('public');
    $file = UploadedFile::fake()->image('tomato.jpg');

    $response = $this->actingAs($user)->postJson(route('ingredients.createUpdate'), [
        'name' => 'Tomato',
        'image' => $file,
    ]);

    $response->assertStatus(200);
    Storage::disk('public')->assertExists('ingredients/' . $file->hashName());
    $this->assertDatabaseHas('ingredients', ['name' => 'Tomato']);
});

it('deletes an ingredient', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $ingredient = Ingredient::factory()->create();

    $response = $this->actingAs($user)->deleteJson(route('ingredients.delete'), ['id' => $ingredient->id]);

    $response->assertStatus(200);
    $this->assertSoftDeleted('ingredients', ['id' => $ingredient->id]);
});

it('bulk deletes ingredients', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $ingredients = Ingredient::factory()->count(3)->create();

    $ids = $ingredients->pluck('id')->toArray();

    $response = $this->actingAs($user)->deleteJson(route('ingredients.bulkDelete'), [
        'ids' => $ids,
    ]);

    $response->assertStatus(200);

    foreach ($ids as $id) {
        $this->assertSoftDeleted('ingredients', ['id' => $id]);
    }
});

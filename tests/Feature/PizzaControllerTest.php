<?php

use App\Models\Pizza;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('fetches pizzas with datatable', function () {
    /** @var Tests\TestCase $this */

    Pizza::factory()->count(5)->create();
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson(route('pizzas.dataTable', [
        'per_page' => 2,
        'page' => 1,
        'sort' => 'name',
        'sort_desc' => false,
        'search' => '',
        'filters' => [],
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

it('creates a Pizza', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();

    $data = [
        'name' => 'Tomato',
        'description' => 'Fresh tomato',
        'price' => '5.99',
        'is_available' => true,
    ];

    $response = $this->actingAs($user)->postJson(route('pizzas.createUpdate'), $data);

    $response->assertStatus(200);
    $response->assertJsonFragment(['name' => 'Tomato']);

    $this->assertDatabaseHas('pizzas', ['name' => 'Tomato']);
});

it('updates a Pizza', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $Pizza = Pizza::factory()->create(['name' => 'Old Name']);

    $response = $this->actingAs($user)->postJson(route('pizzas.createUpdate'), [
        'id' => $Pizza->id,
        'name' => 'Updated Name',
        'price' => '6.99',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('pizzas', ['id' => $Pizza->id, 'name' => 'Updated Name', 'price' => '6.99']);
});

it('uploads a image for Pizza', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();

    Storage::fake('public');
    $file = UploadedFile::fake()->image('tomato.jpg');

    $response = $this->actingAs($user)->postJson(route('pizzas.createUpdate'), [
        'name' => 'Tomato',
        'image' => $file,
        'price' => '6.99',
    ]);

    $response->assertStatus(200);

    Storage::disk('public')->assertExists('pizzas/' . $file->hashName());
    $this->assertDatabaseHas('pizzas', ['name' => 'Tomato']);
});

it('deletes a Pizza', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $Pizza = Pizza::factory()->create();

    $response = $this->actingAs($user)->deleteJson(route('pizzas.delete'), ['id' => $Pizza->id]);

    $response->assertStatus(200);
    $this->assertSoftDeleted('pizzas', ['id' => $Pizza->id]);
});

it('bulk deletes pizzas', function () {
    /** @var Tests\TestCase $this */

    $user = User::factory()->create();
    $pizzas = Pizza::factory()->count(3)->create();

    $ids = $pizzas->pluck('id')->toArray();

    $response = $this->actingAs($user)->deleteJson(route('pizzas.bulkDelete'), [
        'ids' => $ids,
    ]);

    $response->assertStatus(200);

    foreach ($ids as $id) {
        $this->assertSoftDeleted('pizzas', ['id' => $id]);
    }
});

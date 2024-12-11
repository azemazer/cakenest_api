<?php

use App\Models\Cupcake;
use App\Models\User;

use function Pest\Laravel\deleteJson;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('Admin can create a cupcake', function() {
    $user = User::factory()
    ->create(["is_admin" => true]);

    $title = 'Cupcake';
    $price =  5;

    $response = $this->actingAs($user)
    ->postJson('/api/cupcake', [
        'title' => $title,
        'price' => $price,
    ])->assertCreated();
    
    $correspondingCupcakes = Cupcake::where('price', $price)
    ->where('title', $title)
    ->get();

    expect(sizeof($correspondingCupcakes))->toBe(1);
});

test('Lambda user and guest cannot create a cupcake', function () {
    $user = User::factory()
    ->create(["is_admin" => false]);

    $title = 'Cupcake';
    $price =  5;

    $response = $this->actingAs($user)
    ->postJson('/api/cupcake', [
        'title' => $title,
        'price' => $price,
    ])->assertStatus(401);
    
    $correspondingCupcakes = Cupcake::where('price', $price)
    ->where('title', $title)
    ->get();

    expect(sizeof($correspondingCupcakes))->toBe(0);
});

test('Anyone can get a cupcake', function() {

    $user = User::factory()
    ->create(["is_admin" => false]);
    $cupcake = Cupcake::factory()->create();
    $responseGet = $this->actingAs($user)
    ->getJson('/api/cupcake/' . $cupcake->id);
    expect(json_decode($responseGet->decodeResponseJson()->json))->toMatchObject([
        'id' => $cupcake->id,
    ]);

});

test('Admin can update a cupcake', function() {
    $user = User::factory()
    ->create(["is_admin" => true]);

    $cupcake = Cupcake::factory()->create();

    $newTitle = 'New Cupcake';
    $newPrice = 6;

    $responseEdit = $this->actingAs($user)->putJson('/api/cupcake/' . $cupcake->id, [
        'title' => $newTitle,
        'price' => $newPrice
    ]);

    expect(json_decode($responseEdit->decodeResponseJson()->json))->toMatchObject([
        'id' => $cupcake->id,
        'title' => $newTitle,
        'price' => $newPrice
    ]);
});

test('Lambda user and guest cannot update a cupcake', function () {
    $admin = User::factory()
    ->create(["is_admin" => true]);

    $user = User::factory()->create();

    $title = 'Cupcake';
    $price =  5;

    $response = $this->actingAs($admin)->postJson('/api/cupcake', [
        'title' => $title,
        'price' => $price,
    ])->assertCreated();

    $id = json_decode($response->decodeResponseJson()->json)->id;
    $correspondingCupcake = Cupcake::find($id);

    expect((object) $correspondingCupcake->getAttributes())->toMatchObject([
        'id' => $id,
        'title' => $title,
        'price' => $price
    ]);
    $newTitle = 'New Cupcake';
    $newPrice = 6;

    $responseEdit = $this->actingAs($user)->putJson('/api/cupcake/' . $id, [
        'title' => $newTitle,
        'price' => $newPrice
    ]);

    $responseEdit->assertStatus(401);
    $correspondingCupcake = Cupcake::find($id);

    expect((object) $correspondingCupcake->getAttributes())->toMatchObject([
        'id' => $id,
        'title' => $title,
        'price' => $price
    ]);
});

test('Admin can delete a cupcake', function() {
    $user = User::factory()
    ->create(["is_admin" => true]);

    $title = 'Cupcake';
    $price =  5;

    $response = $this->actingAs($user)->postJson('/api/cupcake', [
        'title' => $title,
        'price' => $price,
    ])->assertCreated();

    $id = json_decode($response->decodeResponseJson()->json)->id;

    $response = $this->actingAs($user)->deleteJson('/api/cupcake/' . $id)->assertStatus(200);

    expect(Cupcake::find($id))->toBeNull();
});

test('Lambda user and guest cannot delete a cupcake', function () {
    $user = User::factory()
    ->create(["is_admin" => true]);

    $cupcake = Cupcake::factory()->create();

    $response = $this->deleteJson('/api/cupcake/' . $cupcake->id)->assertStatus(401);

    expect(Cupcake::find($cupcake->id))->toBeInstanceOf(Cupcake::class);
});
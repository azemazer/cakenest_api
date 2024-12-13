<?php

use App\Models\Cart;
use App\Models\Cupcake;
use App\Models\User;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('user created can have a designed cart', function () {
    $user = User::factory()
    ->has(Cart::factory())
    ->create();

    $response = $this->actingAs($user)->getJson('/api/cart')
    ->assertStatus(200);

});

test('non-user cannot access cart route', function () {
    $response = $this->getJson('/api/cart')
    ->assertStatus(401);
});

test('user can add multiple cupcakes to cart', function () {
    $user = User::factory()
    ->has(Cart::factory())
    ->create();

    $cupcakes = Cupcake::factory()->count(5)->create();
    $cupcakes = array_map(function ($cupcake) {
        $cupcake['cupcake'] = $cupcake['id'];
        $cupcake['quantity'] = 1;
        return $cupcake;
    }, $cupcakes->toArray());

    $response = $this->actingAs($user)->postJson('api/cart', [
        'cupcakes' => $cupcakes
    ])->assertStatus(200);

});

test('user can remove a cupcake from the cart', function () {
    $user = User::factory()->create();
    $cart = Cart::factory()->for($user)->hasAttached(Cupcake::factory(), ['quantity' => 1])->create();

    $cupcake = $user->cart->cupcakes[0];
    
    $response = $this->actingAs($user)->postJson('api/cart/remove/' . $cupcake->id)
    ->assertStatus(200);
    
    $cart = Cart::findOrFail($cart->id);
    expect(sizeof($cart->cupcakes->toArray()))->toBe(0);
});

test('user can empty his cart', function () {
    $user = User::factory()
    ->has(Cart::factory())
    ->create();

    $cupcakes = Cupcake::factory()->count(5)->create();
    $cupcakes = array_map(function ($cupcake) {
        $cupcake['cupcake'] = $cupcake['id'];
        $cupcake['quantity'] = 1;
        return $cupcake;
    }, $cupcakes->toArray());

    $response = $this->actingAs($user)->postJson('api/cart', [
        'cupcakes' => $cupcakes
    ])->assertStatus(200);

    expect(sizeof($user->cart->cupcakes))->toBe(5);
    
    $response = $this->actingAs($user)->postJson('api/cart/empty')
    ->assertStatus(200);

    expect(sizeof($user->cart->cupcakes))->toBe(0);

});
<?php

use App\Models\Cupcake;
use App\Models\User;
use App\Models\Promocode;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('Valid promocode reduces command price', function (){
    $user = User::factory()->create();
    $promocode = Promocode::factory()->create();
    $cupcakes = Cupcake::factory()->count(5)->create();

    $cupcakes_array = array_map(function ($cupcake) {
        $cupcake["quantity"] = 1;
        $cupcake["cupcake"] = $cupcake["id"];
        return $cupcake;
    }, $cupcakes->toArray());

    $response = $this->actingAs($user)
    ->postJson('api/command', [
        'cupcakes' => $cupcakes_array,
        'promocode' => $promocode->code,
    ])
    // ;
    // dd($response);
    ->assertCreated();

    $total = 0;
    foreach ($cupcakes_array as $cupcake){
        $total += $cupcake["price"] * $cupcake["quantity"];
    }
    $total_reductions = $total - ($total * $promocode->percentage / 100);
    // dd([$total_reductions, $total, $response->json()]);
    expect($response->json('total_reductions'))->toBe((int)$total_reductions);

    // 'cupcakes' => $cupcakes->toArray(),

});

test('Invalid promocode doesnt create command', function (){
    $user = User::factory()->create();
    $cupcakes = Cupcake::factory()->count(5)->create();

    $cupcakes_array = array_map(function ($cupcake) {
        $cupcake["quantity"] = 1;
        $cupcake["cupcake"] = $cupcake["id"];
        return $cupcake;
    }, $cupcakes->toArray());

    $response = $this->actingAs($user)
    ->postJson('api/command', [
        'cupcakes' => $cupcakes_array,
        'promocode' => "blablah",
    ])
    ->assertStatus(403);
});

test('Expired promocode doesnt create command', function (){
    $user = User::factory()->create();
    $promocode = Promocode::factory()->create([
        "validity_date" => new DateTime('yesterday')
    ]);
    $cupcakes = Cupcake::factory()->count(5)->create();

    $cupcakes_array = array_map(function ($cupcake) {
        $cupcake["quantity"] = 1;
        $cupcake["cupcake"] = $cupcake["id"];
        return $cupcake;
    }, $cupcakes->toArray());

    $response = $this->actingAs($user)
    ->postJson('api/command', [
        'cupcakes' => $cupcakes_array,
        'promocode' => $promocode->code,
    ])
    ->assertStatus(403);
});


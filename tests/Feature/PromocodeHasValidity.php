<?php

use App\Models\Promocode;
use App\Models\User;

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('User can submit promocode if valid', function(){
    $user = User::factory()->create();
    $promocode = Promocode::factory()->create();

    $response = $this->actingAs($user)
    ->getJson('api/promocode/submit?code=' . $promocode->code)
    ->assertStatus(200);

    expect(json_decode($response->decodeResponseJson()->json))->toHaveKeys([
        'id', 'code', 'validity_date', 'percentage'
    ]);

});

test('User cant submit promocode if invalid', function(){
    $user = User::factory()->create();
    $false_code = "blable";

    $response = $this->actingAs($user)
    ->getJson('api/promocode/submit?code=' . $false_code)
    ->assertStatus(404);
});

test('User cant submit promocode if expired', function(){
    $user = User::factory()->create();
    $promocode = Promocode::factory()->create([
        "validity_date" => new DateTime('tomorrow')
    ]);

    $response = $this->actingAs($user)
    ->getJson('api/promocode/submit?code=' . $promocode->code)
    ->assertStatus(403);
    //...
});
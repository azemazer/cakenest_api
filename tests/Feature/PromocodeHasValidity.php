<?php

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('User can submit promocode if valid', function(){
    //...
});

test('User cant submit promocode if invalid', function(){
    //...
});

test('User cant submit promocode if expired', function(){
    //...
});
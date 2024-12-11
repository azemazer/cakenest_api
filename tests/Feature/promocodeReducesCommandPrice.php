<?php

test('example', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('Valid promocode reduces command price', function (){
    //...
});

test('Invalid promocode doesnt reduce command price', function (){
    //...
});

test('Expired promocode doesnt reduce command price', function (){
    //...
});


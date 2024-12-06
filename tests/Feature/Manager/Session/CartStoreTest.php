<?php

use Binafy\LaravelCart\LaravelCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetUp\Models\Product;
use Tests\SetUp\Models\User;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

afterEach(function () {
    session()->flush();
});

test('can store product in cart with facade', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product = Product::query()->create(['title' => 'Product 1']);

    // Store item in cart
    LaravelCart::driver('session')->storeItem($product, $user->id);

    // Assertions
    expect(count(session("cart_$user->id")))->toBe(1);
});

test('can store products in cart with facade', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product1 = Product::query()->create(['title' => 'Product 1']);
    $product2 = Product::query()->create(['title' => 'Product 2']);

    $items = [
        [
            'itemable' => $product1,
            'quantity' => 2,
        ],
        [
            'itemable' => $product2,
            'quantity' => 1,
        ],
    ];

    LaravelCart::driver('session')->storeItems($items);

    // Assertions
    expect(count(session("cart_$user->id")))->toBe(2);
});

test('can store product in cart with facade and login user', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product = Product::query()->create(['title' => 'Product 1']);

    // Store item in cart
    LaravelCart::driver('session')->storeItem($product);

    // Assertions
    expect(count(session("cart_$user->id")))->toBe(1);
});

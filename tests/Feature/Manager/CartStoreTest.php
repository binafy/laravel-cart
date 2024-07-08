<?php

use Binafy\LaravelCart\LaravelCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetUp\Models\Product;
use Tests\SetUp\Models\User;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

/*
 * Use `RefreshDatabase` for delete migration data for each test.
 */
uses(RefreshDatabase::class);

test('can store product in cart with facade', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product = Product::query()->create(['title' => 'Product 1']);

    $cart = LaravelCart::driver('database')->storeItem($product, $user->id);

    // DB Assertions
    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 1);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);
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

    $cart = LaravelCart::driver('database')->storeItems($items);

    // DB Assertions
    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 2);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $product1->id,
        'itemable_type' => $product1::class,
        'quantity' => 2,
    ]);
});

test('can store product in cart with facade and login user', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product = Product::query()->create(['title' => 'Product 1']);

    // Store item in cart
    LaravelCart::driver('database')->storeItem($product);

    // DB Assertions
    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 1);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);
});

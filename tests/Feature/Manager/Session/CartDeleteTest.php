<?php

use Binafy\LaravelCart\LaravelCart;
use Binafy\LaravelCart\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetUp\Models\Product;
use Tests\SetUp\Models\User;

use function Pest\Laravel\assertDatabaseCount;

test('can remove an item from the cart with facade', function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product1 = Product::query()->create(['title' => 'Product 1']);
    $product2 = Product::query()->create(['title' => 'Product 2']);
    $product3 = Product::query()->create(['title' => 'Product 3']);
    $product4 = Product::query()->create(['title' => 'Product 4']);

    $items = [
        [
            'itemable' => $product1,
            'quantity' => 2,
        ],
        [
            'itemable' => $product2,
            'quantity' => 1,
        ],
        [
            'itemable' => $product3,
            'quantity' => 5,
        ],
        [
            'itemable' => $product4,
            'quantity' => 3,
        ],
    ];

    // Store items to cart
    LaravelCart::driver('session')->storeItems($items);

    // Delete Item from cart
    LaravelCart::driver('session')->removeItem($product1);

    // Assertions
    expect(count(session("cart_$user->id")))->toBe(3);

    LaravelCart::driver('session')->removeItem($product2);
    expect(count(session("cart_$user->id")))->toBe(2);

    LaravelCart::driver('session')->removeItem($product1);
    expect(count(session("cart_$user->id")))->toBe(2);
});

test('can empty the cart', function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product1 = Product::query()->create(['title' => 'Product 1']);
    $product2 = Product::query()->create(['title' => 'Product 2']);
    $product3 = Product::query()->create(['title' => 'Product 3']);
    $product4 = Product::query()->create(['title' => 'Product 4']);

    $items = [
        [
            'itemable' => $product1,
            'quantity' => 2,
        ],
        [
            'itemable' => $product2,
            'quantity' => 1,
        ],
        [
            'itemable' => $product3,
            'quantity' => 5,
        ],
        [
            'itemable' => $product4,
            'quantity' => 3,
        ],
    ];

    // Store items to cart
    LaravelCart::driver('session')->storeItems($items);
    expect(count(session("cart_$user->id")))->toBe(4);

    // Remove all items from cart
    LaravelCart::driver('session')->emptyCart();
    expect(count(session("cart_$user->id")))->toBe(0);
});

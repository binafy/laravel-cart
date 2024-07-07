<?php

use Binafy\LaravelCart\LaravelCart;
use Binafy\LaravelCart\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetUp\Models\Product;
use Tests\SetUp\Models\User;

use function Pest\Laravel\assertDatabaseCount;

/*
 * Use `RefreshDatabase` for delete migration data for each test.
 */
uses(RefreshDatabase::class);

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
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItems($items);

    // Delete Item from cart
    LaravelCart::removeItem($product1);

    // DB Assertions
    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 3);

    LaravelCart::removeItem($product2);
    assertDatabaseCount('cart_items', 2);

    LaravelCart::removeItem($product1);
    assertDatabaseCount('cart_items', 2);
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
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItems($items);

    assertDatabaseCount('cart_items', 4);

    // Remove all items from cart
    LaravelCart::emptyCart();
    assertDatabaseCount('cart_items', 0);
});

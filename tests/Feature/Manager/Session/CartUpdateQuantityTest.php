<?php

use Binafy\LaravelCart\LaravelCart;
use Binafy\LaravelCart\Models\Cart;
use Binafy\LaravelCart\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetUp\Models\Product;
use Tests\SetUp\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

/*
 * Use `RefreshDatabase` for delete migration data for each test.
 */
uses(RefreshDatabase::class);

test('can increase quantity of the item in cart with facade', function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product = Product::query()->create(['title' => 'Product 1']);

    // Store item to cart
    LaravelCart::driver('session')->storeItem([
        'itemable' => $product,
        'quantity' => 1,
    ]);

    expect(session("cart_$user->id")[0]['quantity'])->toBe(1);

    // Increase quantity
    LaravelCart::driver('session')->increaseQuantity($product, 2);

    expect(session("cart_$user->id")[0]['quantity'])->toBe(3);
});

test('can decrease quantity of the item in cart with facade', function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product = Product::query()->create(['title' => 'Product 1']);

    // Store item to cart
    LaravelCart::driver('session')->storeItem([
        'itemable' => $product,
        'quantity' => 3,
    ], 2);

    expect(session("cart_2")[0]['quantity'])->toBe(3);

    // Increase quantity
    LaravelCart::driver('session')->decreaseQuantity($product, 2, 2);

    expect(session("cart_2")[0]['quantity'])->toBe(1);
});

test('can not increase quantity of the item in cart with facade when item not found', function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product1 = Product::query()->create(['title' => 'Product 1']);
    $product2 = Product::query()->create(['title' => 'Product 2']);

    // Store item to cart
    LaravelCart::driver('session')->storeItem([
        'itemable' => $product1,
        'quantity' => 1,
    ], 2);

    expect(session("cart_2")[0]['quantity'])->toBe(1);

    // Increase quantity
    LaravelCart::driver('session')->increaseQuantity($product2, 2);

    expect(session("cart_2")[0]['quantity'])->toBe(1);
})->expectExceptionMessage('The item not found');

test('can not decrease quantity of the item in cart with facade when item not found', function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product1 = Product::query()->create(['title' => 'Product 1']);
    $product2 = Product::query()->create(['title' => 'Product 2']);

    // Store item to cart
    LaravelCart::driver('session')->storeItem([
        'itemable' => $product1,
        'quantity' => 3,
    ], 2);

    expect(session("cart_2")[0]['quantity'])->toBe(3);

    // Decrease quantity
    LaravelCart::driver('session')->decreaseQuantity($product2, 2);

    expect(session("cart_2")[0]['quantity'])->toBe(3);
})->expectExceptionMessage('The item not found');

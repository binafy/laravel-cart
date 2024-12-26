<?php

use Binafy\LaravelCart\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetUp\Models\Product;
use Tests\SetUp\Models\User;

use function Pest\Laravel\assertDatabaseCount;

/*
 * Use `RefreshDatabase` for delete migration data for each test.
 */
uses(RefreshDatabase::class);

test('can set option for cart', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product1 = Product::query()->create(['title' => 'Product 1']);

    // Store items to cart
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItem($product1);

    // Set options
    $cart->items()->first()->setOption('size', 34);

    // Assertions
    expect($cart->items()->first()->getOption('size'))->toBe(34);

    // DB Assertions
    assertDatabaseCount('cart_items', 1);
});

test('can get all option of one cart item', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product1 = Product::query()->create(['title' => 'Product 1']);

    // Store items to cart
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItem($product1);

    // Set options
    $cart->items()->first()->setOption('size', 34);

    // Assertions
    expect($cart->items()->first()->getOptions())->toBe(['size' => 34]);

    // DB Assertions
    assertDatabaseCount('cart_items', 1);
});

test('can add option for one cart item', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product1 = Product::query()->create(['title' => 'Product 1']);

    // Store items to cart
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItem($product1);

    // Set options
    $cart->items()->first()->addOption('size', 34);
    $cart->items()->first()->addOption('address', 'something');

    // Assertions
    expect($cart->items()->first()->getOption('size'))
        ->toBe(34)
        ->and($cart->items()->first()->getOption('address'))
        ->toBe('something');

    // DB Assertions
    assertDatabaseCount('cart_items', 1);
});

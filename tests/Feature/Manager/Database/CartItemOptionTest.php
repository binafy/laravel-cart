<?php

use Binafy\LaravelCart\Events\LaravelCartEmptyEvent;
use Binafy\LaravelCart\Events\LaravelCartRemoveItemEvent;
use Binafy\LaravelCart\Events\LaravelCartStoreItemEvent;
use Binafy\LaravelCart\LaravelCart;
use Binafy\LaravelCart\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\SetUp\Models\Product;
use Tests\SetUp\Models\User;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

/*
 * Use `RefreshDatabase` for delete migration data for each test.
 */
uses(RefreshDatabase::class);

test('can set option for cart', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.com']);
    auth()->login($user);
    $product1 = Product::query()->create(['title' => 'Product 1']);

    // Store items to cart
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItem($product1);

    // Set options
    LaravelCart::driver('database')->setOption('size', 34);

    // Assertions
    expect(LaravelCart::driver('database')->getOption('size'))->toBe(34);

    // DB Assertions
    assertDatabaseCount('cart_items', 1);
});

test('can set option for cart with specific id', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.com']);
    auth()->login($user);
    $product1 = Product::query()->create(['title' => 'Product 1']);
    $product2 = Product::query()->create(['title' => 'Product 2']);

    // Store items to cart
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItem($product1);
    $cart->storeItem($product2);

    // Set options
    LaravelCart::driver('database')->setOption('size', 80, 2);

    // Assertions
    expect(LaravelCart::driver('database')->getOption('size', 2))
        ->toBe(80)
        ->and(LaravelCart::driver('database')->getOptions(2))
        ->toBe(['size' => 80]);

    // Add option
    LaravelCart::driver('database')->addOption('address', 'something', 2);

    // DB Assertions
    assertDatabaseCount('cart_items', 2);
});

test('can get all option of one cart item', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.com']);
    auth()->login($user);
    $product1 = Product::query()->create(['title' => 'Product 1']);

    // Store items to cart
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItem($product1);

    // Set options
    LaravelCart::driver('database')->setOption('size', 34);

    // Assertions
    expect(LaravelCart::driver('database')->getOptions())->toBe(['size' => 34]);

    // DB Assertions
    assertDatabaseCount('cart_items', 1);
});

test('can add option for one cart item', closure: function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.com']);
    auth()->login($user);
    $product1 = Product::query()->create(['title' => 'Product 1']);

    // Store items to cart
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItem($product1);

    // Set options
    LaravelCart::driver('database')->addOption('size', 34);
    LaravelCart::driver('database')->addOption('address', 'something');

    // Assertions
    expect(LaravelCart::driver('database')->getOption('size'))
        ->toBe(34)
        ->and(LaravelCart::driver('database')->getOption('address'))
        ->toBe('something');

    // DB Assertions
    assertDatabaseCount('cart_items', 1);
});

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

    // Create cart
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);

    // Store item to cart
    $cartItem = new CartItem([
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);

    $cart->items()->save($cartItem);

    assertDatabaseHas('cart_items', ['quantity' => 1]);

    // Increase quantity
    LaravelCart::increaseQuantity($product, 2);

    assertDatabaseHas('cart_items', ['quantity' => 3]);
});

test('can decrease quantity of the item in cart with facade', function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product = Product::query()->create(['title' => 'Product 1']);

    // Create cart
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);

    // Store item to cart
    $cartItem = new CartItem([
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 3,
    ]);

    $cart->items()->save($cartItem);

    assertDatabaseHas('cart_items', ['quantity' => 3]);

    // Increase quantity
    LaravelCart::decreaseQuantity($product, 2);

    assertDatabaseHas('cart_items', ['quantity' => 1]);
});

test('can not increase quantity of the item in cart with facade when item not found', function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product1 = Product::query()->create(['title' => 'Product 1']);
    $product2 = Product::query()->create(['title' => 'Product 2']);

    // Create cart
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);

    // Store item to cart
    $cartItem = new CartItem([
        'itemable_id' => $product1->id,
        'itemable_type' => $product1::class,
        'quantity' => 1,
    ]);

    $cart->items()->save($cartItem);

    assertDatabaseHas('cart_items', ['quantity' => 1]);

    // Increase quantity
    LaravelCart::increaseQuantity($product2 , 2);

    assertDatabaseHas('cart_items', ['quantity' => 1]);
    assertDatabaseMissing('cart_items', ['quantity' => 3]);
})->expectExceptionMessage('The item not found');

test('can not decrease quantity of the item in cart with facade when item not found', function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    auth()->login($user);

    $product1 = Product::query()->create(['title' => 'Product 1']);
    $product2 = Product::query()->create(['title' => 'Product 2']);

    // Create cart
    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);

    // Store item to cart
    $cartItem = new CartItem([
        'itemable_id' => $product1->id,
        'itemable_type' => $product1::class,
        'quantity' => 3,
    ]);

    $cart->items()->save($cartItem);

    assertDatabaseHas('cart_items', ['quantity' => 3]);

    // Decrease quantity
    LaravelCart::decreaseQuantity($product2 , 2);

    assertDatabaseHas('cart_items', ['quantity' => 3]);
    assertDatabaseMissing('cart_items', ['quantity' => 1]);
})->expectExceptionMessage('The item not found');

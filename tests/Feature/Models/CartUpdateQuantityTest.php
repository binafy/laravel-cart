<?php

use Binafy\LaravelCart\Events\LaravelCartDecreaseQuantityEvent;
use Binafy\LaravelCart\Events\LaravelCartIncreaseQuantityEvent;
use Binafy\LaravelCart\Models\Cart;
use Binafy\LaravelCart\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\SetUp\Models\Product;
use Tests\SetUp\Models\User;

use function Pest\Laravel\assertDatabaseHas;

/*
 * Use `RefreshDatabase` for delete migration data for each test.
 */
uses(RefreshDatabase::class);

test('can increase quantity of the item in cart', function () {
    Event::fake();

    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
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
    $cart->increaseQuantity($product, 2);

    assertDatabaseHas('cart_items', ['quantity' => 3]);

    // Event Assertion
    Event::assertDispatched(LaravelCartIncreaseQuantityEvent::class);
});

test('can decrease quantity of the item in cart', function () {
    Event::fake();

    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
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
    $cart->decreaseQuantity($product, 2);

    assertDatabaseHas('cart_items', ['quantity' => 1]);

    // Event Assertion
    Event::assertDispatched(LaravelCartDecreaseQuantityEvent::class);
});

test('can not increase quantity of the item in cart when item not found', function () {
    Event::fake();

    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product1 = Product::query()->create(['title' => 'Product 1']);

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
    $product2 = Product::query()->create(['title' => 'Product 2']);
    $cart->increaseQuantity($product2, 2);

    assertDatabaseHas('cart_items', ['quantity' => 1]);

    // Event Assertion
    Event::assertNotDispatched(LaravelCartIncreaseQuantityEvent::class);
})->expectExceptionMessage('The item not found');

test('can not decrease quantity of the item in cart when item not found', function () {
    Event::fake();

    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product1 = Product::query()->create(['title' => 'Product 1']);

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

    // Increase quantity
    $product2 = Product::query()->create(['title' => 'Product 2']);
    $cart->decreaseQuantity($product2, 2);

    assertDatabaseHas('cart_items', ['quantity' => 3]);

    // Event Assertion
    Event::assertNotDispatched(LaravelCartDecreaseQuantityEvent::class);
})->expectExceptionMessage('The item not found');

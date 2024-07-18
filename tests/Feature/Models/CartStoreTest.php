<?php

use Binafy\LaravelCart\Events\LaravelCartStoreItemEvent;
use Binafy\LaravelCart\Models\Cart;
use Binafy\LaravelCart\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\SetUp\Models\Product;
use Tests\SetUp\Models\User;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function PHPUnit\Framework\assertInstanceOf;

/*
 * Use `RefreshDatabase` for delete migration data for each test.
 */
uses(RefreshDatabase::class);

test('can store product in cart', function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product = Product::query()->create(['title' => 'Product 1']);

    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cartItem = new CartItem([
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);

    $cart->items()->save($cartItem);

    // Assertions
    assertInstanceOf($product::class, $cartItem->itemable()->first());
    assertInstanceOf($cart::class, $cartItem->cart()->first());

    // DB Assertions
    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 1);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);
});

test('can store product in cart with custom table name from config', function () {
    config()->set([
        'laravel-cart.carts.table' => 'custom_carts',
        'laravel-cart.cart_items.table' => 'custom_cart_items',
    ]);

    Artisan::call('migrate:refresh');

    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product = Product::query()->create(['title' => 'Product 1']);

    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);

    $cartItem = new CartItem([
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);

    $cart->items()->save($cartItem);

    // Assertions
    assertInstanceOf($product::class, $cartItem->itemable()->first());

    // DB Assertions
    assertDatabaseCount('custom_carts', 1);
    assertDatabaseCount('custom_cart_items', 1);
    assertDatabaseHas('custom_cart_items', [
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);
});

test('can store product in cart with firstOrCreateWithItems scope', function () {
    Event::fake();

    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product = Product::query()->create(['title' => 'Product 1']);

    $cart = Cart::query()->firstOrCreateWithStoreItems($product, 1, $user->id);

    // DB Assertions
    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 1);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);

    // Event Assertion
    Event::assertDispatched(LaravelCartStoreItemEvent::class);
});

test('can store product in cart with firstOrCreateWithItems scope when user sign-in', function () {
    Event::fake();

    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product = Product::query()->create(['title' => 'Product 1']);

    auth()->login($user);

    $cart = Cart::query()->firstOrCreateWithStoreItems($product, 1);

    // DB Assertions
    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 1);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $product->id,
        'itemable_type' => $product::class,
        'quantity' => 1,
    ]);

    // Event Assertion
    Event::assertDispatched(LaravelCartStoreItemEvent::class);
});

test('can store multiple products in cart', function () {
    Event::fake();

    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product1 = Product::query()->create(['title' => 'Product 1']);
    $product2 = Product::query()->create(['title' => 'Product 1']);
    $product3 = Product::query()->create(['title' => 'Product 1']);

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
    ];

    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItems($items);

    // DB Assertions
    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 3);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $product1->id,
        'itemable_type' => $product1::class,
        'quantity' => 2,
    ]);

    // Event Assertion
    Event::assertDispatchedTimes(LaravelCartStoreItemEvent::class, 3);
});

test('get correct price with calculated quantity', function () {
    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $product1 = Product::query()->create(['title' => 'Product 1', 'price' => 15000]);
    $product2 = Product::query()->create(['title' => 'Product 1', 'price' => 25000]);
    $product3 = Product::query()->create(['title' => 'Product 1', 'price' => 35000]);

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
    ];

    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
    $cart->storeItems($items);

    // Assertions
    \PHPUnit\Framework\assertEquals(230000, $cart->calculatedPriceByQuantity());

    // DB Assertions
    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 3);
    assertDatabaseHas('cart_items', [
        'itemable_id' => $product1->id,
        'itemable_type' => $product1::class,
        'quantity' => 2,
    ]);
});

test('can not store product in cart when item is not instance of cartable', function () {
    Event::fake();

    $user = User::query()->create(['name' => 'Milwad', 'email' => 'milwad.dev@gmail.comd']);
    $user2 = User::query()->create(['name' => 'Binafy', 'email' => 'binafy23@gmail.comd']);

    $cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);

    $item['itemable'] = $user2;
    $item['quantity'] = 2;

    $cart->storeItem($item);

    // DB Assertions
    assertDatabaseCount('carts', 1);
    assertDatabaseCount('cart_items', 0);
    assertDatabaseMissing('cart_items', [
        'itemable_id' => $user2->id,
        'itemable_type' => $user2::class,
        'quantity' => 2,
    ]);

    // Event Assertion
    Event::assertNotDispatched(LaravelCartStoreItemEvent::class);
})->expectExceptionMessage('The item must be an instance of Cartable');

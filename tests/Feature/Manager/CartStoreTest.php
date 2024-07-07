<?php

use Binafy\LaravelCart\Models\Cart;
use Binafy\LaravelCart\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\SetUp\Models\Product;
use Tests\SetUp\Models\User;
use Binafy\LaravelCart\LaravelCart;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function PHPUnit\Framework\assertInstanceOf;

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

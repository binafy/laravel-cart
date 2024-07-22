<?php

use Binafy\LaravelCart\Events\LaravelCartEmptyEvent;
use Binafy\LaravelCart\Events\LaravelCartRemoveItemEvent;
use Binafy\LaravelCart\Events\LaravelCartStoreItemEvent;
use Binafy\LaravelCart\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
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
    $cart->setOption($option);
});

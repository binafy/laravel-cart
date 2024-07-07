<?php

use Binafy\LaravelCart\LaravelCart;
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

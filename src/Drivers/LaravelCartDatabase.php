<?php

namespace Binafy\LaravelCart\Drivers;

use Binafy\LaravelCart\Models\Cart;
use Illuminate\Database\Eloquent\Model;

class LaravelCartDatabase implements Driver
{
    /**
     * Store item in cart.
     */
    public function storeItem(Model|array $item, int|null $userId = null): static
    {
        if (is_null($userId)) {
            $userId = auth()->id();
        }

        $cart = Cart::query()->firstOrCreate(['user_id' => $userId]);
        $cart->storeItem($item);

        return $this;
    }

    /**
     * Store multiple items in cart.
     */
    public function storeItems(array $items): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);
        $cart->storeItems($items);

        return $this;
    }

    public function increaseQuantity()
    {
        // TODO: Implement increaseQuantity() method.
    }

    public function decreaseQuantity()
    {
        // TODO: Implement decreaseQuantity() method.
    }

    public function removeItem()
    {
        // TODO: Implement removeItem() method.
    }

    public function empty()
    {
        // TODO: Implement empty() method.
    }
}

<?php

namespace Binafy\LaravelCart\Observers;

use Binafy\LaravelCart\Cartable;
use Binafy\LaravelCart\Models\CartItem;

class CartItemObserve
{
    /**
     * Handle the CartItem "creating" event.
     */
    public function creating(CartItem $cartItem): void
    {
        if (! new $cartItem->itemable_type instanceof Cartable) {
            throw new \RuntimeException('The item must be an instance of Cartable');
        }
    }

    /**
     * Handle the CartItem "updating" event.
     */
    public function updating(CartItem $cartItem): void
    {
        if (! new $cartItem->itemable_type instanceof Cartable) {
            throw new \RuntimeException('The item must be an instance of Cartable');
        }
    }

    /**
     * Handle the CartItem "saving" event.
     */
    public function saving(CartItem $cartItem): void
    {
        if (! new $cartItem->itemable_type instanceof Cartable) {
            throw new \RuntimeException('The item must be an instance of Cartable');
        }
    }
}

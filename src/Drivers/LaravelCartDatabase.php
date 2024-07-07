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

    /**
     * Increase the quantity of the item.
     */
    public function increaseQuantity(Model $item, int $quantity = 1): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);
        $item = $cart->items()->firstWhere('itemable_id', $item->getKey());

        if (! $item) {
            throw new \RuntimeException('The item not found');
        }

        $item->increment('quantity', $quantity);

        return $this;
    }

    /**
     * Decrease the quantity of the item.
     */
    public function decreaseQuantity(Model $item, int $quantity = 1): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);
        $item = $cart->items()->firstWhere('itemable_id', $item->getKey());

        if (! $item) {
            throw new \RuntimeException('The item not found');
        }

        $item->decrement('quantity', $quantity);

        return $this;
    }

    /**
     * Remove a single item from the cart
     */
    public function removeItem(Model $item): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);
        $itemToDelete = $cart->items()->find($item->getKey());

        if ($itemToDelete) {
            $itemToDelete->delete();
        }

        return $this;
    }

    /**
     * Remove every item from the cart
     */
    public function empty(): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => auth()->id()]);
        $cart->emptyCart();

        return $this;
    }
}

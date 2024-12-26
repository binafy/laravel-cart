<?php

namespace Binafy\LaravelCart\Drivers;

use Binafy\LaravelCart\Models\Cart;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class LaravelCartDatabase implements Driver
{
    /**
     * Store item in cart.
     */
    public function storeItem(Model|array $item, ?int $userId = null): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $this->resolveUserId($userId)]);
        $cart->storeItem($item);

        return $this;
    }

    /**
     * Store multiple items in cart.
     */
    public function storeItems(array $items, ?int $userId = null): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $this->resolveUserId($userId)]);
        $cart->storeItems($items);

        return $this;
    }

    /**
     * Increase the quantity of the item.
     */
    public function increaseQuantity(Model $item, int $quantity = 1, ?int $userId = null): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $this->resolveUserId($userId)]);
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
    public function decreaseQuantity(Model $item, int $quantity = 1, ?int $userId = null): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $this->resolveUserId($userId)]);
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
    public function removeItem(Model $item, ?int $userId = null): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $this->resolveUserId($userId)]);
        $itemToDelete = $cart->items()->find($item->getKey());
        $itemToDelete?->delete();

        return $this;
    }

    /**
     * Remove every item from the cart
     */
    public function emptyCart(?int $userId = null): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $this->resolveUserId($userId)]);
        $cart->emptyCart();

        return $this;
    }

    /**
     * Get option for item.
     */
    public function getOption(string $option, ?int $itemId = null, ?int $userId = null): mixed
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $this->resolveUserId($userId)]);
        $items = $cart->items()->when(! is_null($itemId), function (Builder $builder) use ($itemId) {
            $builder->where('id', $itemId);
        });

        return $items->first()->getOption($option);
    }

    /**
     * Get all options of one item.
     */
    public function getOptions(?int $itemId = null, ?int $userId = null): mixed
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $this->resolveUserId($userId)]);
        $items = $cart->items()->when(! is_null($itemId), function (Builder $builder) use ($itemId) {
            $builder->where('id', $itemId);
        });

        return $items->first()->getOptions();
    }

    /**
     * Set option for item.
     */
    public function setOption(string $key, mixed $value, ?int $itemId = null, ?int $userId = null): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $this->resolveUserId($userId)]);
        $items = $cart->items()->when(! is_null($itemId), function (Builder $builder) use ($itemId) {
            $builder->where('id', $itemId);
        });

        $items->first()->setOption($key, $value);

        return $this;
    }

    /**
     * Get option for item.
     */
    public function addOption(string $key, mixed $value, ?int $itemId = null, ?int $userId = null): static
    {
        $cart = Cart::query()->firstOrCreate(['user_id' => $this->resolveUserId($userId)]);
        $items = $cart->items()->when(! is_null($itemId), function (Builder $builder) use ($itemId) {
            $builder->where('id', $itemId);
        });

        $items->first()->addOption($key, $value);

        return $this;
    }

    /**
     * Resolve the user ID, defaulting to the authenticated user.
     */
    protected function resolveUserId(?int $userId): int
    {
        return $userId ?? auth()->id();
    }
}

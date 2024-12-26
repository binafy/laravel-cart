<?php

namespace Binafy\LaravelCart\Drivers;

use Binafy\LaravelCart\Cartable;
use Illuminate\Database\Eloquent\Model;

class LaravelCartSession implements Driver
{
    protected const SESSION_KEY_PREFIX = 'cart_';

    /**
     * Store item in cart.
     */
    public function storeItem(Model|array $item, ?int $userId = null): static
    {
        $userId = $this->resolveUserId($userId);
        $cart = $this->getCart($userId);

        if (is_array($item)) {
            $item['itemable_id'] = $item['itemable']->getKey();
            $item['itemable_type'] = get_class($item['itemable']);
            $item['quantity'] = (int) $item['quantity'];

            if ($item['itemable'] instanceof Cartable) {
                $cart[] = $item;

                session([$this->sessionKey($userId) => $cart]);
            } else {
                throw new \RuntimeException(sprintf('The item must be an instance of %s', Cartable::class));
            }
        } else {
            if ($item instanceof Cartable) {
                $cart[] = $item;

                session([$this->sessionKey($userId) => $cart]);
            }
        }

        return $this;
    }

    /**
     * Store multiple items in cart.
     */
    public function storeItems(array $items, ?int $userId = null): static
    {
        foreach ($items as $item) {
            $this->storeItem($item, $userId);
        }

        return $this;
    }

    /**
     * Increase the quantity of the item.
     */
    public function increaseQuantity(Model $item, int $quantity = 1, ?int $userId = null): static
    {
        $userId = $this->resolveUserId($userId);
        $cart = $this->getCart($userId);

        foreach ($cart as &$cartItem) {
            if ($cartItem['itemable_id'] === $item->getKey() && $cartItem['itemable_type'] === get_class($item)) {
                $cartItem['quantity'] += $quantity;
                session([$this->sessionKey($userId) => $cart]);

                return $this;
            }
        }

        throw new \RuntimeException('The item not found');
    }

    /**
     * Decrease the quantity of the item.
     */
    public function decreaseQuantity(Model $item, int $quantity = 1, ?int $userId = null): static
    {
        $userId = $this->resolveUserId($userId);
        $cart = $this->getCart($userId);

        foreach ($cart as &$cartItem) {
            if ($cartItem['itemable_id'] === $item->getKey() && $cartItem['itemable_type'] === get_class($item)) {
                $cartItem['quantity'] = max($cartItem['quantity'] - $quantity, 0);
                session([$this->sessionKey($userId) => $cart]);

                return $this;
            }
        }

        throw new \RuntimeException('The item not found');
    }

    /**
     * Remove a single item from the cart.
     */
    public function removeItem(Model $item, ?int $userId = null): static
    {
        $userId = $this->resolveUserId($userId);
        $cart = $this->getCart($userId);
        $cart = array_filter($cart, fn ($cartItem) => $cartItem['itemable_id'] !== $item->getKey());

        session([$this->sessionKey($userId) => $cart]);

        return $this;
    }

    /**
     * Remove every item from the cart.
     */
    public function emptyCart(?int $userId = null): static
    {
        $userId = $this->resolveUserId($userId);
        session([$this->sessionKey($userId) => []]);

        return $this;
    }

    /**
     * Get the cart from the session.
     */
    protected function getCart(?int $userId = null): array
    {
        $userId = $this->resolveUserId($userId);

        return session($this->sessionKey($userId), []);
    }

    /**
     * Resolve the session key for a user.
     */
    protected function sessionKey(int $userId): string
    {
        return self::SESSION_KEY_PREFIX.$userId;
    }

    /**
     * Resolve the user ID, defaulting to the authenticated user.
     */
    protected function resolveUserId(?int $userId): int
    {
        return $userId ?? auth()->id();
    }
}

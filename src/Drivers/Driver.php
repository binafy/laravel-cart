<?php

namespace Binafy\LaravelCart\Drivers;

use Illuminate\Database\Eloquent\Model;

interface Driver
{
    public function storeItem(Model|array $item, ?string $userId = null): Driver;

    public function storeItems(array $items, ?string $userId = null): Driver;

    public function increaseQuantity(Model $item, int $quantity = 1, ?string $userId = null): Driver;

    public function decreaseQuantity(Model $item, int $quantity = 1, ?string $userId = null): Driver;

    public function removeItem(Model $item, ?string $userId = null): Driver;

    public function emptyCart(?string $userId = null): Driver;
}

<?php

namespace Binafy\LaravelCart\Drivers;

use Illuminate\Database\Eloquent\Model;

interface Driver
{
    public function storeItem(Model|array $item, ?int $userId = null): Driver;

    public function storeItems(array $items, ?int $userId = null): Driver;

    public function increaseQuantity(Model $item, int $quantity = 1, ?int $userId = null): Driver;

    public function decreaseQuantity(Model $item, int $quantity = 1, ?int $userId = null): Driver;

    public function removeItem(Model $item, ?int $userId = null): Driver;

    public function emptyCart(?int $userId = null): Driver;
}

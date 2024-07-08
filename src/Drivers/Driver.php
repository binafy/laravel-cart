<?php

namespace Binafy\LaravelCart\Drivers;

use Illuminate\Database\Eloquent\Model;

interface Driver
{
    public function storeItem(Model|array $item, ?int $userId = null): Driver;

    public function storeItems(array $items): Driver;

    public function increaseQuantity(Model $item, int $quantity = 1): Driver;

    public function decreaseQuantity(Model $item, int $quantity = 1): Driver;

    public function removeItem(Model $item): Driver;

    public function emptyCart(): Driver;
}

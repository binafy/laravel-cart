<?php

namespace Binafy\LaravelCart\Drivers;

use Illuminate\Database\Eloquent\Model;

interface Driver
{
    public function storeItem(Model|array $item, int|null $userId = null): static;

    public function storeItems(array $items): static;

    public function increaseQuantity(Model $item, int $quantity = 1): static;

    public function decreaseQuantity();

    public function removeItem();

    public function empty();
}

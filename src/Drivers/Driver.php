<?php

namespace Binafy\LaravelCart\Drivers;

interface Driver
{
    public function storeItem();

    public function storeItems();

    public function increaseQuantity();

    public function decreaseQuantity();

    public function removeItem();

    public function empty();
}

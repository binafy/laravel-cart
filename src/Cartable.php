<?php

namespace Binafy\LaravelCart;

interface Cartable
{
    public function getPrice(): int;

    public function getKey(): mixed;
}

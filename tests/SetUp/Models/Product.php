<?php

namespace Tests\SetUp\Models;

use Binafy\LaravelCart\Cartable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements Cartable
{
    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = ['title', 'price'];

    /**
     * Get the correct price.
     */
    public function getPrice(): int
    {
        return $this->price;
    }
}

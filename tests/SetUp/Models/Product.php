<?php

namespace Tests\SetUp\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = ['title', 'price'];
}

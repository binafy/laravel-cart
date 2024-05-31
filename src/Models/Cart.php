<?php

namespace Binafy\LaravelCart\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = ['user_id'];

    /**
     * Relation one-to-many, CartItem model.
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}

<?php

namespace Binafy\LaravelCart\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = ['cart_id', 'itemable_id', 'itemable_type', 'quantity'];

    /**
     * Relation polymorphic, inverse one-to-one or many relationship.
     */
    public function itemable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}

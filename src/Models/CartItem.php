<?php

namespace Binafy\LaravelCart\Models;

use Binafy\LaravelCart\Cartable;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = ['cart_id', 'itemable_id', 'itemable_type', 'quantity'];

    /**
     * Create a new instance of the model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('laravel-cart.cart_items.table', 'cart_items');
    }

    /**
     * Bootstrap the model and its traits.
     */
    public static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (! new $model->itemable_type instanceof Cartable) {
                throw new \Exception('The item must be an instance of Cartable');
            }
        });

        static::updating(function ($model) {
            if (! new $model->itemable_type instanceof Cartable) {
                throw new \Exception('The item must be an instance of Cartable');
            }
        });
    }

    /**
     * Relation polymorphic, inverse one-to-one or many relationship.
     */
    public function itemable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relation one-to-many, Cart model.
     */
    public function cart(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }
}

<?php

namespace Binafy\LaravelCart\Models;

use Binafy\LaravelCart\Observers\CartItemObserve;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;

#[ObservedBy([CartItemObserve::class])]
class CartItem extends Model
{
    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * Create a new instance of the model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('laravel-cart.cart_items.table', 'cart_items');
    }

    /**
     * Get option.
     */
    public function getOption(string $option): mixed
    {
        $options = json_decode($this->options);

        return $options[$option] ?? null;
    }

    /**
     * Get options.
     */
    public function getOptions(): mixed
    {
        return json_decode($this->options);
    }

    /**
     * Get options.
     */
    public function setOption(string $key, mixed $value): static
    {
        $this->update(['options' => json_encode([$key => $value])]);

        return $this;
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

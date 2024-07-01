<?php

namespace Binafy\LaravelCart\Models;

use Binafy\LaravelCart\Cartable;
use Illuminate\Database\Eloquent\Builder;
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
     * The relations to eager load on every query.
     *
     * @var string[]
     */
    protected $with = ['items'];

    /**
     * Create a new instance of the model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('laravel-cart.carts.table', 'carts');
    }

    // Relations

    /**
     * Relation one-to-many, CartItem model.
     */
    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    // Scopes

    /**
     * @throws \Exception
     */
    public function scopeFirstOrCreateWithStoreItems(
        Builder $query,
        Model $item,
        int $quantity = 1,
        ?int $userId = null
    ): Builder {
        if (is_null($userId)) {
            $userId = auth()->id();
        }
        if (! $item instanceof Cartable) {
            throw new \Exception('The item must be an instance of Cartable');
        }

        $cart = $query->firstOrCreate(['user_id' => $userId]);
        $cartItem = new CartItem([
            'itemable_id' => $item->getKey(),
            'itemable_type' => $item::class,
            'quantity' => $quantity,
        ]);

        $cart->items()->save($cartItem);

        return $query;
    }

    // Methods

    /**
     * Calculate price by quantity of items.
     */
    public function calculatedPriceByQuantity(): int
    {
        $totalPrice = 0;
        foreach ($this->items()->get() as $item) {
            $totalPrice += (int) $item->quantity * (int) $item->itemable->getPrice();
        }

        return $totalPrice;
    }

    /**
     * Store multiple items.
     */
    public function storeItems(array $items): Cart
    {
        foreach ($items as $item) {
            $this->storeItem($item);
        }

        return $this;
    }

    /**
     * Store cart item in cart.
     */
    public function storeItem(Model|array $item): Cart
    {
        if (is_array($item)) {
            $item['itemable_id'] = $item['itemable']->getKey();
            $item['itemable_type'] = get_class($item['itemable']);
            $item['quantity'] = (int) $item['quantity'];
        }

        $this->items()->create($item);

        return $this;
    }

    /**
     * Remove a single item from the cart
     */
    public function removeItem(Model $item): Cart
    {
        $itemToDelete = $this->items()->find($item->getKey());

        if ($itemToDelete) {
            $itemToDelete->delete();
        }

        return $this;
    }

    /**
     * Remove every item from the cart
     */
    public function emptyCart(): Cart
    {
        $this->items()->delete();

        return $this;
    }
}

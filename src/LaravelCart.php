<?php

namespace Binafy\LaravelCart;

/**
 * @method static \Binafy\LaravelCart\Drivers\Driver driver(string|null $driver = null)
 * @method static \Binafy\LaravelCart\Drivers\Driver storeItem(\Illuminate\Database\Eloquent\Model|array $item, int|null $userId = null)
 * @method static \Binafy\LaravelCart\Drivers\Driver storeItems(array $items)
 * @method static \Binafy\LaravelCart\Drivers\Driver increaseQuantity(\Illuminate\Database\Eloquent\Model $item, int $quantity = 1)
 * @method static \Binafy\LaravelCart\Drivers\Driver decreaseQuantity(\Illuminate\Database\Eloquent\Model $item, int $quantity = 1)
 * @method static \Binafy\LaravelCart\Drivers\Driver removeItem(\Illuminate\Database\Eloquent\Model $item)
 * @method static \Binafy\LaravelCart\Drivers\Driver emptyCart()
 * @method static \Binafy\LaravelCart\Drivers\Driver getOption(string $option, ?int $itemId = null, ?int $userId = null)
 * @method static \Binafy\LaravelCart\Drivers\Driver getOptions(?int $itemId = null, ?int $userId = null)
 * @method static \Binafy\LaravelCart\Drivers\Driver setOption(string $key, mixed $value, ?int $itemId = null, ?int $userId = null)
 * @method static \Binafy\LaravelCart\Drivers\Driver addOption(string $key, mixed $value, ?int $itemId = null, ?int $userId = null)
 * @method static string getDefaultDriver()
 * @method static void setDefaultDriver(string $name)
 *
 * @see \Binafy\LaravelCart\Manager\LaravelCartManager
 */
class LaravelCart extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-cart';
    }
}

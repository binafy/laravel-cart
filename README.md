# Laravel Cart

[![PHP Version Require](http://poser.pugx.org/binafy/laravel-cart/require/php)](https://packagist.org/packages/binafy/laravel-cart)
[![Latest Stable Version](http://poser.pugx.org/binafy/laravel-cart/v)](https://packagist.org/packages/binafy/laravel-cart)
[![Total Downloads](http://poser.pugx.org/binafy/laravel-cart/downloads)](https://packagist.org/packages/binafy/laravel-cart)
[![License](http://poser.pugx.org/binafy/laravel-cart/license)](https://packagist.org/packages/binafy/laravel-cart)
[![Passed Tests](https://github.com/binafy/laravel-cart/actions/workflows/tests.yml/badge.svg)](https://github.com/binafy/laravel-cart/actions/workflows/tests.yml)

<a name="introduction"></a>
## Introduction

Easily integrate credit card and payment processing functionality into your Laravel application with Laravel Card. This package provides a simple and secure way to handle card payments, subscriptions, and transactions, allowing you to focus on building your application's core features.

Laravel Card is designed to be highly customizable and flexible, making it easy to adapt to your specific use case. Whether you're building an e-commerce platform, a subscription-based service, or a donation system, this package provides a solid foundation for handling card payments in your Laravel application.

## Features:

- Secure card information storage and management
- Support for multiple payment gateways
- Recurring payment and subscription management
- Robust validation and error handling
- Highly customizable and flexible architecture

<a name="installation"></a>
## Installation

You can install the package with Composer:

```bash
composer require binafy/laravel-cart
```

<a name="publish"></a>
## Publish

If you want to publish a config file you can use this command:

```shell
php artisan vendor:publish --tag="laravel-cart-config"
```

<a name="usage"></a>
## Usage

<a name="store-cart"></a>
### Store Cart

For storing a new cart, you can use `Cart` model:

```php
use \Binafy\LaravelCart\Models\Cart;

$cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
```

<a name="store-items-for-a-cart"></a>
### Store Items For a Cart

If you want to store items for cart, first you need to create a cart and attach items to cart:

```php
$cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
$cartItem = new CartItem([
    'itemable_id' => $itemable->id,
    'itemable_type' => $itemable::class,
    'quantity' => 1,
]);

$cart->items()->save($cartItem);
```

If you may to access the items of one cart, you can use `items` relation that exists in Cart model.

> There is no need to use any Interface or something for itemable.   

<a name="access-itemable"></a>
### Access Itemable

If you want to access to itemable in `CartItem`, you can use `itemable` relation:

```php
$cartItem = new CartItem([
    'itemable_id' => $itemable->id,
    'itemable_type' => $itemable::class,
    'quantity' => 1,
]);

$cartItem->itemable()->first(); // Return Model Instance
```

<a name="create-cart-with-storing-items"><a>
### Create Cart With Storing Items

```php
Cart::query()->firstOrCreateWithStoreItems(
    item: $product,
    quantity: 1,
    userId: $user->id
);
```

<a name="store-multiple-items"></a>
### Store multiple items

If you may to store multiple items for a cart, you can use `storeItems` method:

```php
$items = [
    [
        'itemable' => $product1,
        'quantity' => 2,
    ],
    [
        'itemable' => $product2,
        'quantity' => 1,
    ],
    [
        'itemable' => $product3,
        'quantity' => 5,
    ],
];

$cart = Cart::query()->firstOrCreate(['user_id' => $user->id]);
$cart->storeItems($items);
```

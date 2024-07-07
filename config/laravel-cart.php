<?php

return [
    /*
     * Users
     *
     * This value is about the users table, foreign key and, ... .
     */
    'users' => [
        /*
         * The user table name.
         */
        'table' => 'users',

        /*
         * The user foreign key.
         */
        'foreign_id' => 'user_id',
    ],

    /*
     * Carts
     *
     * This value is about the cart table name, foreign key and, ... .
     */
    'carts' => [
        /*
         * The cart table name.
         */
        'table' => 'carts',

        /*
         * The cart foreign key name.
         */
        'foreign_id' => 'cart_id',
    ],

    /*
     * Cart Items
     *
     * This value is about the items of one cart.
     */
    'cart_items' => [
        /*
         * The cart items table name.
         */
        'table' => 'cart_items',
    ],

    /*
     * Driver
     */
    'driver' => [
        'default' => 'database',
    ],
];

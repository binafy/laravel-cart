<?php

namespace Tests\SetUp\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'email'];
}

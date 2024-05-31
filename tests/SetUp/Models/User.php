<?php

namespace Tests\SetUp\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * Fillable columns.
     *
     * @var string[]
     */
    protected $fillable = ['name', 'email'];
}

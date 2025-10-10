<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $userTableName = config('laravel-cart.users.table', 'users');
        $userForeignName = config('laravel-cart.users.foreign_id', 'user_id');
        $type = config('laravel-cart.users.foreign_key_type', 'id');
        $table = config('laravel-cart.carts.table', 'carts');

        Schema::create($table, function (Blueprint $table) use ($userTableName, $userForeignName, $type) {
            $table->id();

            if ($type === 'ulid') {
                $table->foreignUlid($userForeignName)
                    ->constrained($userTableName)
                    ->cascadeOnDelete();
            } elseif ($type === 'uuid') {
                $table->foreignUuid($userForeignName)
                    ->constrained($userTableName)
                    ->cascadeOnDelete();
            } else {
                $table->foreignId($userForeignName)
                    ->constrained($userTableName)
                    ->cascadeOnDelete();
            }

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $table = config('laravel-cart.carts.table', 'carts');

        Schema::dropIfExists($table);
    }
};

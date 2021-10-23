<?php

declare(strict_types=1);

namespace Kazuto\LaravelMoney\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Kazuto\LaravelMoney\Money;

class MoneyCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): Money
    {
        return Money::fromInt($value);
    }

    public function set($model, string $key, $value, array $attributes): int
    {
        return $value instanceof Money ? $value->toInt() : $value;
    }
}

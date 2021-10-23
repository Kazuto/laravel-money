<?php

declare(strict_types=1);

namespace Kazuto\LaravelMoney;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kazuto\LaravelMoney\Money
 */
class MoneyFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'money';
    }
}

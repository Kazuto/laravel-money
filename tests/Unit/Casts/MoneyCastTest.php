<?php

declare(strict_types=1);

namespace Tests\Unit\Casts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kazuto\LaravelMoney\Casts\MoneyCast;
use Kazuto\LaravelMoney\Money;

uses(RefreshDatabase::class);

test('get() returns new MoneyCast', function (): void {
    $money = (new MoneyCast)->get(TestModel::class, 'money', 5, []);

    expect($money)
        ->toBeInstanceOf(Money::class);
})->group('money');

test('set() returns int if int is passed', function (): void {
    $money = (new MoneyCast)->set(TestModel::class, 'money', 5, []);

    expect($money)
        ->toBeInt();
})->group('money');

test('set() returns int if Money is passed', function (): void {
    $money = (new MoneyCast)->set(TestModel::class, 'money', Money::fromInt(5), []);

    expect($money)
        ->toBeInt();
})->group('money');

class MoneyCastTest extends Model
{
}

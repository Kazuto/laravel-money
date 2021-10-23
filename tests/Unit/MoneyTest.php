<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Kazuto\LaravelMoney\Money;

uses(RefreshDatabase::class);

test('fromInt() returns Money object', function (): void {
    $money = Money::fromInt(5);

    expect($money)
        ->toBeInstanceOf(Money::class);
})->group('money');

test('fromFloat() returns Money object', function (): void {
    $money = Money::fromFloat(5.00);

    expect($money)
        ->toBeInstanceOf(Money::class);
})->group('money');

test('toText() returns localized string', function ($locale, $value, $expected): void {
    Config::set('money.locale_iso', $locale);

    $money = Money::fromInt($value);

    expect($money->toText())
        ->toBeString()
        ->toEqual($expected);
})->group('money')->with([
    ['en_US', 5438521, '$54,385.21'],
    ['de_DE', 213248, "2.132,48\u{a0}€"],
    ['de_CH', 87541, "CHF\u{a0}875.41"],
    ['jp_JP', 248600, '¥2,486'],
    ['en_GB', 45325, '£453.25'],
    ['es_ES', 25481387512, "254.813.875,12\u{a0}€"],
    ['zh_CN', 862174, '¥8,621.74'],
    ['sv_SE', 745152921, "7\u{a0}451\u{a0}529,21\u{a0}kr"],
]);

test('toInt() returns without decimals', function ($locale, $value): void {
    Config::set('money.locale_iso', $locale);

    $money = Money::fromInt($value);

    expect($money->toInt())
        ->toBeInt()
        ->toEqual($value);
})->group('money')->with([
    ['en_US', 543423],
    ['de_DE', 13845],
    ['de_CH', 871456786],
    ['jp_JP', 25456],
    ['en_GB', 6831235],
    ['es_ES', 32],
    ['zh_CN', 4783],
    ['sv_SE', 7358369],
]);

test('toFloat() returns with decimals', function ($locale, $value, $expected): void {
    Config::set('money.locale_iso', $locale);

    $money = Money::fromInt($value);

    expect($money->toFloat())
        ->toBeFloat()
        ->toEqual($expected);
})->group('money')->with([
    ['en_US', 543, 5.43],
    ['de_DE', 138, 1.38],
    ['de_CH', 871, 8.71],
    ['jp_JP', 100, 1.00],
    ['en_GB', 884, 8.84],
    ['es_ES', 213, 2.13],
    ['zh_CN', 581, 5.81],
    ['sv_SE', 921, 9.21],
]);

test('jsonSerialize() returns array', function ($locale, $value, $expected): void {
    Config::set('money.locale_iso', $locale);
    $money = Money::fromInt($value);

    expect($money->toArray())
        ->toBeArray()
        ->toEqual($expected);
})->group('money')->with([
    ['en_US', 543, ['value' => 543, 'formatted' => '$5.43', 'currency' => 'USD', 'symbol' => '$']],
    ['de_DE', 138, ['value' => 138, 'formatted' => "1,38\u{a0}€", 'currency' => 'EUR', 'symbol' => '€']],
    ['de_CH', 871, ['value' => 871, 'formatted' => "CHF\u{a0}8.71", 'currency' => 'CHF', 'symbol' => 'CHF']],
    ['jp_JP', 100, ['value' => 100, 'formatted' => '¥1', 'currency' => 'JPY', 'symbol' => '¥']],
    ['en_GB', 884, ['value' => 884, 'formatted' => '£8.84', 'currency' => 'GBP', 'symbol' => '£']],
    ['es_ES', 213, ['value' => 213, 'formatted' => "2,13\u{a0}€", 'currency' => 'EUR', 'symbol' => '€']],
    ['zh_CN', 581, ['value' => 581, 'formatted' => '¥5.81', 'currency' => 'CNY', 'symbol' => '¥']],
    ['sv_SE', 921, ['value' => 921, 'formatted' => "9,21\u{a0}kr", 'currency' => 'SEK', 'symbol' => 'kr']],
]);

test('add() with integer returns updated Money object', function ($value, $expectedInt, $expectedFloat): void {
    $money = Money::fromInt(500);

    $money->add($value);

    expect($money->toInt())
        ->toBeInt()
        ->toEqual($expectedInt);

    expect($money->toFloat())
        ->toBeFloat()
        ->toEqual($expectedFloat);
})->group('money')->with([
    [500, 1000, 10.00],
    [1.23, 623, 6.23],
    [0, 500, 5.00],
    [7.89, 1289, 12.89],
    [5478, 5978, 59.78],
]);

test('add() with Money::fromInt() returns updated Money object', function ($value, $expectedInt, $expectedFloat): void {
    $money = Money::fromInt(500);

    $money->add(Money::fromInt($value));

    expect($money->toInt())
        ->toBeInt()
        ->toEqual($expectedInt);

    expect($money->toFloat())
        ->toBeFloat()
        ->toEqual($expectedFloat);
})->group('money')->with([
    [500, 1000, 10.00],
    [123, 623, 6.23],
    [0, 500, 5.00],
    [789, 1289, 12.89],
    [5478, 5978, 59.78],
]);

test('add() with Money::fromFloat() returns updated Money object', function ($value, $expectedInt, $expectedFloat): void {
    $money = Money::fromInt(500);

    $money->add(Money::fromFloat($value));

    expect($money->toInt())
        ->toBeInt()
        ->toEqual($expectedInt);

    expect($money->toFloat())
        ->toBeFloat()
        ->toEqual($expectedFloat);
})->group('money')->with([
    [5.00, 1000, 10.00],
    [1.23, 623, 6.23],
    [0.00, 500, 5.00],
    [7.89, 1289, 12.89],
    [54.78, 5978, 59.78],
]);

test('substract() with integer returns updated Money object', function ($value, $expectedInt, $expectedFloat): void {
    $money = Money::fromInt(500);

    $money->substract($value);

    expect($money->toInt())
        ->toBeInt()
        ->toEqual($expectedInt);

    expect($money->toFloat())
        ->toBeFloat()
        ->toEqual($expectedFloat);
})->group('money')->with([
    [500, 0, 0.00],
    [1.23, 377, 3.77],
    [0, 500, 5.00],
    [7.89, -289, -2.89],
    [5478, -4978, -49.78],
]);

test('substract() with Money::fromInt() returns updated Money object', function ($value, $expectedInt, $expectedFloat): void {
    $money = Money::fromInt(500);

    $money->substract(Money::fromInt($value));

    expect($money->toInt())
        ->toBeInt()
        ->toEqual($expectedInt);

    expect($money->toFloat())
        ->toBeFloat()
        ->toEqual($expectedFloat);
})->group('money')->with([
    [500, 0, 0.00],
    [123, 377, 3.77],
    [0, 500, 5.00],
    [789, -289, -2.89],
    [5478, -4978, -49.78],
]);

test('substract() with Money::fromFloat() returns updated Money object', function ($value, $expectedInt, $expectedFloat): void {
    $money = Money::fromInt(500);

    $money->substract(Money::fromFloat($value));

    expect($money->toInt())
        ->toBeInt()
        ->toEqual($expectedInt);

    expect($money->toFloat())
        ->toBeFloat()
        ->toEqual($expectedFloat);
})->group('money')->with([
    [5.00, 0, 0.00],
    [1.23, 377, 3.77],
    [0.00, 500, 5.00],
    [7.89, -289, -2.89],
    [54.78, -4978, -49.78],
]);

test('multiply() with integral on Money::fromInt() returns updated Money object', function ($value, $expectedInt, $expectedFloat): void {
    $money = Money::fromInt(123);

    $money->multiply($value);

    expect($money->toInt())
        ->toBeInt()
        ->toEqual($expectedInt);

    expect($money->toFloat())
        ->toBeFloat()
        ->toEqual($expectedFloat);
})->group('money')->with([
    [1, 123, 1.23],
    [1.25, 154, 1.54],
    [2, 246, 2.46],
    [2.37, 292, 2.92],
    [5.91, 727, 7.27],
    [7.77, 956, 9.56],
]);

test('multiply() with integral on Money::fromFloat() returns updated Money object', function ($value, $expectedInt, $expectedFloat): void {
    $money = Money::fromFloat(1.23);

    $money->multiply($value);

    expect($money->toInt())
        ->toBeInt()
        ->toEqual($expectedInt);

    expect($money->toFloat())
        ->toBeFloat()
        ->toEqual($expectedFloat);
})->group('money')->with([
    [1, 123, 1.23],
    [1.25, 154, 1.54],
    [2, 246, 2.46],
    [2.37, 292, 2.92],
    [5.91, 727, 7.27],
    [7.77, 956, 9.56],
]);

test('divide() with integral on Money::fromInt() returns updated Money object', function ($value, $expectedInt, $expectedFloat): void {
    $money = Money::fromInt(1235);

    $money->divide($value);

    expect($money->toInt())
        ->toBeInt()
        ->toEqual($expectedInt);

    expect($money->toFloat())
        ->toBeFloat()
        ->toEqual($expectedFloat);
})->group('money')->with([
    [1, 1235, 12.35],
    [1.25, 988, 9.88],
    [2, 618, 6.18],
    [2.37, 521, 5.21],
    [5.91, 209, 2.09],
    [7.77, 159, 1.59],
]);

test('divide() with integral on Money::fromFloat() returns updated Money object', function ($value, $expectedInt, $expectedFloat): void {
    $money = Money::fromFloat(12.35);

    $money->divide($value);

    expect($money->toInt())
        ->toBeInt()
        ->toEqual($expectedInt);

    expect($money->toFloat())
        ->toBeFloat()
        ->toEqual($expectedFloat);
})->group('money')->with([
    [1, 1235, 12.35],
    [1.25, 988, 9.88],
    [2, 618, 6.18],
    [2.37, 521, 5.21],
    [5.91, 209, 2.09],
    [7.77, 159, 1.59],
]);

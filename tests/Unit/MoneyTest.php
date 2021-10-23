<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Kazuto\LaravelMoney\Money;

uses(RefreshDatabase::class);

test('fromInt() returns MoneyFormatter object', function (): void {
    $money = Money::fromInt(5);

    expect($money)
        ->toBeInstanceOf(Money::class);
})->group('money');

test('fromFloat() returns MoneyFormatter object', function (): void {
    $money = Money::fromFloat(5.00);

    expect($money)
        ->toBeInstanceOf(Money::class);
})->group('money');

test('asText() returns localized string', function ($locale, $value, $expected): void {
    Config::set('money.locale_iso', $locale);

    $money = Money::fromInt($value);

    expect($money->asText())
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

test('asInt() returns without decimals', function ($locale, $value): void {
    Config::set('money.locale_iso', $locale);

    $money = Money::fromInt($value);

    expect($money->asInt())
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

test('asFloat() returns with decimals', function ($locale, $value, $expected): void {
    Config::set('money.locale_iso', $locale);

    $money = Money::fromInt($value);

    expect($money->asFloat())
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

    expect($money->asArray())
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

# Money Cast and Facade for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kazuto/laravel-money.svg?style=flat-square)](https://packagist.org/packages/kazuto/laravel-money)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/kazuto/laravel-money/run-tests?label=tests)](https://github.com/kazuto/laravel-money/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/kazuto/laravel-money/Check%20&%20fix%20styling?label=code%20style)](https://github.com/kazuto/laravel-money/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/kazuto/laravel-money.svg?style=flat-square)](https://packagist.org/packages/kazuto/laravel-money)

This package adds a money cast and money facade to simplify storing financial values in the database by adding them as an integer column instead of float, double, or decimal, thus eliminating the possibility of floating point errors and resulting miscalculations.

## Installation

You can install the package via composer:

```bash
composer require kazuto/laravel-money
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Kazuto\LaravelMoney\MoneyServiceProvider"
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Kazuto\LaravelMoney\MoneyServiceProvider" --tag="money-config"
```

This is the contents of the published config file:

```php
return [
  'locale_iso' => 'en_US'
];
```

## Usage

```php
// Base
Money::fromInt(524)->toInt();   // 524
Money::fromInt(524)->toFloat(); // 5.24
Money::fromInt(524)->toText();  // $5.24
Money::fromInt(524)->toArray();
// [
//   'value' => 524,
//   'formatted' => '$5.24',
//   'currency' => 'USD',
//   'symbol' => '$',
// ]

Money::fromFloat(5.24)->toInt();    // 524
Money::fromFloat(5.24)->toFloat();  // 5.24
Money::fromFloat(5.24)->toText();   // $5.24
Money::fromFloat(5.24)->toArray();
// [
//   'value' => 524,
//   'formatted' => '$5.24',
//   'currency' => 'USD',
//   'symbol' => '$',
// ] 


// Math

// add(Money|int|float)
Money::fromInt(524)->add(123);                    // 647
Money::fromInt(524)->add(1.23);                   // 647
Money::fromInt(524)->add(Money::fromFloat(1.23)); // 647

// substract(Money|int|float)
Money::fromInt(524)->substract(123);                    // 401
Money::fromInt(524)->substract(1.23);                   // 401
Money::fromInt(524)->substract(Money::fromFloat(1.23)); // 401

// multiply(int|float)
Money::fromInt(524)->multiply(2);     // 1048
Money::fromInt(524)->multiply(1.23);  // 645 (rounded from 644.52)

// divide(int|float)
Money::fromInt(524)->divide(2);       // 262
Money::fromInt(524)->divide(1.23);    // 426 (rounded from 426.01)

// Comparisons

// isEqualTo(Money|int|float)
Money::fromInt(524)->isEqualTo(524);                    // true
Money::fromInt(524)->isEqualTo(Money::fromFloat(5.24)); // true
Money::fromInt(524)->isEqualTo(1.23);                   // false
Money::fromInt(524)->isEqualTo(Money::fromInt(123));    // false

// isGreaterThan(Money|int|float)
Money::fromInt(524)->isGreaterThan(123);                    // true
Money::fromInt(524)->isGreaterThan(Money::fromFloat(1.23)); // true
Money::fromInt(524)->isGreaterThan(8.58);                   // false
Money::fromInt(524)->isGreaterThan(Money::fromInt(858));    // false
Money::fromInt(524)->isGreaterThan(524);                    // false
Money::fromInt(524)->isGreaterThan(Money::fromFloat(5.24)); // false

// isGreaterThanOrEqual(Money|int|float)
Money::fromInt(524)->isGreaterThanOrEqual(8.58);                   // false
Money::fromInt(524)->isGreaterThanOrEqual(524);                    // true
Money::fromInt(524)->isGreaterThanOrEqual(Money::fromFloat(5.24)); // true

// isLessThan(Money|int|float)
Money::fromInt(524)->isLessThan(8.58);                   // true
Money::fromInt(524)->isLessThan(Money::fromInt(858));    // true
Money::fromInt(524)->isLessThan(123);                    // false
Money::fromInt(524)->isLessThan(Money::fromFloat(1.23)); // false
Money::fromInt(524)->isLessThan(524);                    // false
Money::fromInt(524)->isLessThan(Money::fromFloat(5.24)); // false

// isLessThanOrEqual(Money|int|float)
Money::fromInt(524)->isLessThanOrEqual(2.24);                   // false
Money::fromInt(524)->isLessThanOrEqual(524);                    // true
Money::fromInt(524)->isLessThanOrEqual(Money::fromFloat(5.24)); // true
```

## Testing

```bash
# Unit Tests
composer test:run

# Coverage
composer test:coverage
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Kai Mayer](https://github.com/Kazuto)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

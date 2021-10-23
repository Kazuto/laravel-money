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
$money = Kazuto\Money::fromInt(524);
echo $money->asFloat();

// 5.24
```

```php
$money = Kazuto\Money::fromInt(500);
echo $money->asText();

// $5.00
```

```php
$money = Kazuto\Money::fromInt(524);
echo $money->asArray();

// [
//   'value' => 524,
//   'formatted' => '$5.24',
//   'currency' => 'USD',
//   'symbol' => '$',
// ] 
```

```php
$money = Kazuto\Money::fromFloat(5.24);
echo $money->asText();

// $5.24
```

```php
$money = Kazuto\Money::fromFloat(5.24);
echo $money->asInt();

// 524
```

```php
$money = Kazuto\Money::fromFloat(5.24);
echo $money->asArray();

// [
//   'value' => 524,
//   'formatted' => '$5.24',
//   'currency' => 'USD',
//   'symbol' => '$',
// ] 
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

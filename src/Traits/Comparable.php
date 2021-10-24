<?php

declare(strict_types=1);

namespace Kazuto\LaravelMoney\Traits;

use Kazuto\LaravelMoney\Money;

trait Comparable
{
    private function compare(Money|int|float $value, string $operator = '='): bool
    {
        if ($value instanceof self) {
            return self::__compare($this->toInt(), $value->toInt(), $operator);
        }

        if (is_float($value)) {
            return self::__compare($this->toInt(), (int) ($value * 100), $operator);
        }

        return self::__compare($this->toInt(), $value, $operator);
    }

    private static function __compare(int $firstValue, int $secondValue, string $operator = '='): bool
    {
        switch ($operator) {
        default:
        case '=':
          return $firstValue === $secondValue;

          break;
        case '>':
          return $firstValue > $secondValue;

          break;
        case '>=':
          return $firstValue >= $secondValue;

          break;
        case '<':
          return $firstValue < $secondValue;

          break;
        case '<=':
          return $firstValue <= $secondValue;

          break;
      }
    }
}

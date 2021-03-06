<?php

declare(strict_types=1);

namespace Kazuto\LaravelMoney;

use Illuminate\Support\Facades\Config;
use JsonSerializable;
use Kazuto\LaravelMoney\Traits\Comparable;
use NumberFormatter;

class Money implements JsonSerializable
{
    use Comparable;

    private int $value;

    private NumberFormatter|false $numberFormatter;

    private string|false $currencyCode;

    private string|false $currencySymbol;

    private function __construct($value = 0)
    {
        $this->value = (int) $value;

        $this->numberFormatter = NumberFormatter::create(Config::get('money.locale_iso'), NumberFormatter::CURRENCY);

        $this->currencyCode = $this->numberFormatter->getTextAttribute(NumberFormatter::CURRENCY_CODE);
        $this->currencySymbol = $this->numberFormatter->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
    }

    public static function fromInt(int $value = 0): self
    {
        return new self($value);
    }

    public static function fromFloat(float $value = 0.00): self
    {
        return new self(round($value * 100));
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function toFloat(): float
    {
        return $this->toInt() / 100;
    }

    public function toText(): string
    {
        return $this->numberFormatter->format($this->toFloat());
    }

    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    public function add(self|int|float $value): self
    {
        if ($value instanceof self) {
            $value = $value->toInt();
        }

        if (is_float($value)) {
            $value = (int) ($value * 100);
        }

        $this->value += $value;

        return $this;
    }

    public function substract(self|int|float $value): self
    {
        if ($value instanceof self) {
            $value = $value->toInt();
        }

        if (is_float($value)) {
            $value = (int) ($value * 100);
        }

        $this->value -= $value;

        return $this;
    }

    public function multiply(int|float $value): self
    {
        $this->value = (int) round($this->value * $value);

        return $this;
    }

    public function divide(int|float $value): self
    {
        $this->value = (int) round($this->value / $value);

        return $this;
    }

    public function isEqualTo(self|int|float $value): bool
    {
        return $this->compare($value, '=');
    }

    public function isGreaterThan(self|int|float $value): bool
    {
        return $this->compare($value, '>');
    }

    public function isGreaterThanOrEqual(self|int|float $value): bool
    {
        return $this->compare($value, '>=');
    }

    public function isLessThan(self|int|float $value): bool
    {
        return $this->compare($value, '<');
    }

    public function isLessThanOrEqual(self|int|float $value): bool
    {
        return $this->compare($value, '<=');
    }

    public function jsonSerialize(): array
    {
        return [
            'value' => $this->value,
            'formatted' => $this->toText(),
            'currency' => $this->currencyCode,
            'symbol' => $this->currencySymbol,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Kazuto\LaravelMoney;

use Illuminate\Support\Facades\Config;
use JsonSerializable;
use NumberFormatter;

class Money implements JsonSerializable
{
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

    public function asInt(): int
    {
        return $this->value;
    }

    public function asFloat(): float
    {
        return $this->asInt() / 100;
    }

    public function asText(): string
    {
        return $this->numberFormatter->format($this->asFloat());
    }

    public function asArray(): array
    {
        return $this->jsonSerialize();
    }

    public function jsonSerialize(): array
    {
        return [
            'value' => $this->value,
            'formatted' => $this->asText(),
            'currency' => $this->currencyCode,
            'symbol' => $this->currencySymbol,
        ];
    }
}

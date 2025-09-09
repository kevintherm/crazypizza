<?php

namespace App\ValueObjects;

use JsonSerializable;
use NumberFormatter;

class Money implements JsonSerializable
{
    private string $amount;
    private string $currency;
    private string $locale;

    public function __construct(string $amount, ?string $currency = null, ?string $locale = null)
    {
        // normalize to 2 decimals
        $this->amount = number_format((float) $amount, 2, '.', '');
        $this->currency = $currency ?? config('app.currency');
        $this->locale = $locale ?? config('app.currency_locale');
    }

    public function add(string $value): self
    {
        return new self(bcadd($this->amount, $value, 2));
    }

    public function sub(string $value): self
    {
        return new self(bcsub($this->amount, $value, 2));
    }

    public function mul(string $value): self
    {
        return new self(bcmul($this->amount, $value, 2));
    }

    public function div(string $value): self
    {
        if ($value === "0") {
            throw new \InvalidArgumentException("Division by zero");
        }
        return new self(bcdiv($this->amount, $value, 2));
    }

    public function cmp(string $value): int
    {
        return bccomp($this->amount, $value, 2);
    }

    public function toString(): string
    {
        return $this->amount;
    }

    public function toFloat(): float
    {
        return (float) $this->amount;
    }

    public function __toString(): string
    {
        return $this->amount;
    }

    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    public function format(): string
    {
        $formatter = new NumberFormatter($this->locale, NumberFormatter::CURRENCY);
        return $formatter->formatCurrency($this->toFloat(), $this->currency);
    }

    public static function sum(iterable $items): self
    {
        $total = "0.00";

        foreach ($items as $money) {
            $total = ($money instanceof self) ? bcadd($total, $money->toString(), 2) : bcadd($total, (string) $money, 2);
        }

        return new self($total);
    }
}

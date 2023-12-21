<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Bill;

class Calculator
{
    /**
     * Unit prices defined here based on the membership type.
     * Although not used/activated in code now, can easily be implemented
     * All which is needed is to add a "membership_type" field in the customer data file, which can replace the
     * current unit_price one. (%unit_price%).
     */
    public const PI_PER_UNIT = 100_000;
    public const VAT_RATE_STANDARD = 19;
    public const UNIT_PRICE_BASIC = 50.0;
    public const UNIT_PRICE_PRO = 120.0;

    private float $vatRate;
    private float $unitPrice;

    public function __construct(float $unitPrice, float $vatRate = self::VAT_RATE_STANDARD)
    {
        $this->vatRate = $vatRate;
        $this->unitPrice = $unitPrice;
    }

    public function total(int $pi): float
    {
        return round($this->cost($pi) + $this->vat($pi), 2);
    }

    public function cost(int $pi): float
    {
        return round($this->units($pi) * $this->unitPrice, 2);
    }

    public function vat(int $pi): float
    {
        return round(($this->cost($pi) * $this->vatRate) / 100.0, 2);
    }

    /**
     * Units are rounded up, this is as a Business Defined Rule.
     */
    public function units(int $pi): int
    {
        return (int) ceil($pi / static::PI_PER_UNIT);
    }
}

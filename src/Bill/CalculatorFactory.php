<?php

declare(strict_types=1);

/*
 *
 *     This file is part of medunes/cache-billing.
 *
 *     (c) medunes <contact@medunes.net>
 *
 *     This source file is subject to the MIT license that is bundled
 *     with this source code in the file LICENSE.
 *
 */

namespace App\Bill;

/**
 * Although a dedicated unit to validate input is a legitimate choice, but probably an overkill here
 * Validation prevents the construction of an object in an "invalid" state, which the factory might take care of.
 */
class CalculatorFactory
{
    public static function create(float $unitPrice, float $vatRate): Calculator
    {
        if ($unitPrice <= 0) {
            throw new \InvalidArgumentException(sprintf('Unit price must be a positive number (%f given)', $unitPrice));
        }

        if ($vatRate <= 0 || $vatRate > 100) {
            throw new \InvalidArgumentException(sprintf('VAT rate must be a positive number up to 100 (%f given)', $vatRate));
        }

        return new Calculator($unitPrice, $vatRate);
    }
}

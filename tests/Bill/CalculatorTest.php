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

namespace Test\Bill;

use App\Bill\Calculator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Bill\Calculator
 *
 * @internal
 */
final class CalculatorTest extends TestCase
{
    public function provideSUnitsCases(): array
    {
        return [
            'zero' => ['pi' => 0, 'expected' => 0],
            'very low' => ['pi' => 50, 'expected' => 1],
            'low' => ['pi' => 50_000, 'expected' => 1],
            'almost one down' => ['pi' => 99_999, 'expected' => 1],
            'exactly one' => ['pi' => 100_000, 'expected' => 1],
            'almost one up ' => ['pi' => 100_001, 'expected' => 2],
            'almost two down' => ['pi' => 199_999, 'expected' => 2],
            'exactly two' => ['pi' => 200_000, 'expected' => 2],
            'almost two up ' => ['pi' => 200_001, 'expected' => 3],
        ];
    }

    public function provideVatCases(): array
    {
        return [
            'zero' => ['pi' => 0, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 0.0],
            'very low' => ['pi' => 50, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 9.5],
            'low' => ['pi' => 50_000, 'unitCost' => Calculator::UNIT_PRICE_PRO, 'expected' => 22.8],
            'almost one down' => ['pi' => 99_999, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 9.5],
            'exactly one' => ['pi' => 100_000, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 9.5],
            'almost one up ' => ['pi' => 100_001, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 19.0],
            'almost two down' => ['pi' => 199_999, 'unitCost' => Calculator::UNIT_PRICE_PRO, 'expected' => 45.6],
            'exactly two' => ['pi' => 200_000, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 19.0],
            'almost two up ' => ['pi' => 200_001, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 28.5],
        ];
    }

    public function provideCostCases(): array
    {
        return [
            'zero' => ['pi' => 0, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 0.0],
            'very low' => ['pi' => 50, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 50.0],
            'low' => ['pi' => 50_000, 'unitCost' => Calculator::UNIT_PRICE_PRO, 'expected' => 120.0],
            'almost one down' => ['pi' => 99_999, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 50.0],
            'exactly one' => ['pi' => 100_000, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 50.0],
            'almost one up ' => ['pi' => 100_001, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 100.0],
            'almost two down' => ['pi' => 199_999, 'unitCost' => Calculator::UNIT_PRICE_PRO, 'expected' => 240.0],
            'exactly two' => ['pi' => 200_000, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 100.0],
            'almost two up ' => ['pi' => 200_001, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 150.0],
        ];
    }

    public function provideTotalCases(): array
    {
        return [
            'zero' => ['pi' => 0, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 0.0],
            'very low' => ['pi' => 50, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 59.5],
            'low' => ['pi' => 50_000, 'unitCost' => Calculator::UNIT_PRICE_PRO, 'expected' => 142.8],
            'almost one down' => ['pi' => 99_999, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 59.5],
            'exactly one' => ['pi' => 100_000, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 59.5],
            'almost one up ' => ['pi' => 100_001, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 119.0],
            'almost two down' => ['pi' => 199_999, 'unitCost' => Calculator::UNIT_PRICE_PRO, 'expected' => 285.6],
            'exactly two' => ['pi' => 200_000, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 119.0],
            'almost two up ' => ['pi' => 200_001, 'unitCost' => Calculator::UNIT_PRICE_BASIC, 'expected' => 178.5],
        ];
    }

    /**
     * @dataProvider provideSUnitsCases
     *
     * @covers       \App\Bill\Calculator::units()
     */
    public function testsUnits(int $pi, int $expected): void
    {
        $sut = new Calculator(1234);
        $actual = $sut->units($pi);
        self::assertSame($actual, $expected);
    }

    /**
     * @dataProvider provideVatCases
     *
     * @covers       \App\Bill\Calculator::vat()
     */
    public function testVat(int $pi, float $unitPrice, float $expected): void
    {
        $sut = new Calculator($unitPrice);
        $actual = $sut->vat($pi);
        self::assertSame($actual, $expected);
    }

    /**
     * @dataProvider provideTotalCases
     *
     * @covers       \App\Bill\Calculator::total()
     */
    public function testTotal(int $pi, float $unitPrice, float $expected): void
    {
        $sut = new Calculator($unitPrice);
        $actual = $sut->total($pi);
        self::assertSame($actual, $expected);
    }

    /**
     * @dataProvider provideCostCases
     *
     * @covers       \App\Bill\Calculator::cost()
     */
    public function testCost(int $pi, float $unitPrice, float $expected): void
    {
        $sut = new Calculator($unitPrice);
        $actual = $sut->cost($pi);
        self::assertSame($actual, $expected);
    }
}

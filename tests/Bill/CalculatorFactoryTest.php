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
use App\Bill\CalculatorFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Bill\Calculator
 *
 * @internal
 */
final class CalculatorFactoryTest extends TestCase
{
    public function provideCreateFailureCases(): array
    {
        return [
            'Negative Unit Price' => ['unitPrice' => -1, 'vatRate' => 19.0],
            'Zero Unit Price' => ['unitPrice' => 0, 'vatRate' => 19.0],
            'Negative Vat Rate' => ['unitPrice' => 100_000, 'vatRate' => -1.0],
            'Zero Vat Rate' => ['unitPrice' => 100_000, 'vatRate' => 0],
            'Above 100% Vat Rate' => ['unitPrice' => 100_000, 'vatRate' => 101.0],
        ];
    }

    /**
     * @dataProvider provideCreateFailureCases
     *
     * @covers       \App\Bill\CalculatorFactory::create()
     */
    public function testCreateFailure(float $unitPrice, float $vatRate): void
    {
        $sut = new CalculatorFactory();
        $this->expectException(\InvalidArgumentException::class);
        $actual = $sut->create($unitPrice, $vatRate);
    }

    /**
     * @covers       \App\Bill\CalculatorFactory::create()
     */
    public function testCreate(): void
    {
        $sut = new CalculatorFactory();
        $actual = $sut->create(100_000, 19.0);
        $expected = new Calculator(100_000, 19.0);
        $pi = (int) random_int(0, 1_000_000);
        self::assertSame($actual->total($pi), $expected->total($pi));
        self::assertSame($actual->cost($pi), $expected->cost($pi));
        self::assertSame($actual->vat($pi), $expected->vat($pi));
        self::assertSame($actual->units($pi), $expected->units($pi));
    }
}

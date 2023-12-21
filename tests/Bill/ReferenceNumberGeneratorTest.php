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

use App\Bill\ReferenceNumberGenerator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Bill\ReferenceNumberGenerator
 *
 * @internal
 */
final class ReferenceNumberGeneratorTest extends TestCase
{
    public function provideGenerateCases(): array
    {
        return [
            'numby' => ['billNumber' => '1234', 'year' => '2021', 'expected' => '2021-M1234'],
            'stirngy' => ['billNumber' => 'TREF54', 'year' => '2017', 'expected' => '2017-MTREF54'],
        ];
    }

    /**
     * @dataProvider provideGenerateCases
     *
     * @covers       \App\Bill\ReferenceNumberGenerator::generate()
     */
    public function testGenerate(string $billNumber, string $year, string $expected): void
    {
        $sut = new ReferenceNumberGenerator();
        $actual = $sut->generate($year, $billNumber);
        self::assertSame($actual, $expected);
    }
}

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

namespace Test\Compiler;

use App\Compiler\TextCompiler;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Compiler\TextCompiler
 *
 * @internal
 */
final class TextCompilerTest extends TestCase
{
    public function provideSUnitsCases(): array
    {
        return [
            'No placeholder' => [
                'templateText' => 'Cache Billing System',
                'placeholderMap' => ['%datum%' => '01.01.2020'],
                'expected' => 'Cache Billing System',
            ],
            'ignored placeholder' => [
                'templateText' => '$company$ Billing System',
                'placeholderMap' => ['$datum$' => '01.01.2020'],
                'expected' => '$company$ Billing System',
            ],
            'extra placeholder' => [
                'templateText' => '%company% Billing System',
                'placeholderMap' => ['%datum%' => '01.01.2020', '%company%' => 'Cache'],
                'expected' => 'Cache Billing System',
            ],
            'empty text' => [
                'templateText' => '',
                'placeholderMap' => ['%datum%' => '01.01.2020', '%company%' => 'Cache'],
                'expected' => '',
            ],
            'normal use case' => [
                'templateText' => '$company$ Billing System. Copyright (c) $datum$',
                'placeholderMap' => ['$datum$' => '01.01.2020', '$company$' => 'Cache'],
                'expected' => 'Cache Billing System. Copyright (c) 01.01.2020',
            ],
        ];
    }

    /**
     * @dataProvider provideSUnitsCases
     *
     * @covers       \App\Compiler\TextCompiler::compile()
     */
    public function testsUnits(string $templateText, array $placeholderMap, string $expected): void
    {
        $sut = new TextCompiler();
        $actual = $sut->compile($templateText, $placeholderMap);
        self::assertSame($actual, $expected);
    }
}

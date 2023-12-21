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

namespace Test\Reader\Csv;

use App\Exception\CacheBillingException;
use App\Reader\Csv\CsvTableReader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Reader\Csv\CsvTableReader
 *
 * @internal
 */
final class CsvTableReaderTest extends TestCase
{
    /**
     * @var string[]
     */
    private array $configs;

    protected function setUp(): void
    {
        $this->configs = [
            'comma-separated' => [
                'separator' => ',',
                'enclosure' => '"',
                'escape' => '\\',
            ],
            'semi-colon-separated' => [
                'separator' => ';',
                'enclosure' => '"',
                'escape' => '\\',
            ],
            'tab-separated' => [
                'separator' => "\t",
                'enclosure' => '"',
                'escape' => '\\',
            ],
            'pipe-separated' => [
                'separator' => '|',
                'enclosure' => '"',
                'escape' => '\\',
            ],
        ];
    }

    public function provideGetHeaderCases(): array
    {
        return [
            'basic case' => [
                'table' => [
                    ['first_name', 'last_name', 'age', 'sex', 'country'],
                    ['john', 'doe', '23', 'male', 'germany'],
                    ['eric', 'crawly', '55', 'male', 'usa'],
                    ['anna', 'barakova', '66', 'female', 'russia'],
                ],
                'expected' => ['first_name', 'last_name', 'age', 'sex', 'country'],
            ],
        ];
    }

    public function provideRowAssocData(): array
    {
        return [
            'basic case with two empty lines' => [
                'table' => [
                    ['first_name', 'last_name', 'age', 'sex', 'country'],
                    ['john', 'doe', '23', 'male', 'germany'],
                    ['eric', 'crawly', '55', 'male', 'usa'],
                    [0 => null],
                    [0 => null],
                    ['anna', 'barakova', '66', 'female', 'russia'],
                ],
                'expected' => [
                    ['first_name' => 'john', 'last_name' => 'doe', 'age' => '23', 'sex' => 'male', 'country' => 'germany'],
                    ['first_name' => 'eric', 'last_name' => 'crawly', 'age' => '55', 'sex' => 'male', 'country' => 'usa'],
                    ['first_name' => 'anna', 'last_name' => 'barakova', 'age' => '66', 'sex' => 'female', 'country' => 'russia'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideGetHeaderCases
     *
     * @covers       \App\Reader\Csv\CsvTableReader::getHeader
     *
     * @throws CacheBillingException
     */
    public function testGetHeader(array $table, array $expected): void
    {
        foreach ($this->configs as $config) {
            $handle = $this->tableToHandle($table, $config);
            $sut = new CsvTableReader($handle, $config);
            $actual = $sut->getHeader();
            self::assertSame($actual, $expected);
        }
        fclose($handle);
    }

    /**
     * @dataProvider provideRowAssocData
     *
     * @covers       \App\Reader\Csv\CsvTableReader::readRowAssoc()
     *
     * @throws CacheBillingException
     */
    public function testReadRowAssoc(array $table, array $expected): void
    {
        foreach ($this->configs as $config) {
            $handle = $this->tableToHandle($table, $config);
            $sut = new CsvTableReader($handle, $config);

            $actual1 = $sut->readRowAssoc();
            self::assertSame($actual1, $expected[0]);

            $actual2 = $sut->readRowAssoc();
            self::assertSame($actual2, $expected[1]);

            $actual3 = $sut->readRowAssoc();

            self::assertSame($actual3, $expected[2]);

            $actual4 = $sut->readRowAssoc();
            self::assertNull($actual4);
        }
        fclose($handle);
    }

    /**
     * @dataProvider provideRowAssocData
     *
     * @covers       \App\Reader\Csv\CsvTableReader::readRowAssoc()
     *
     * @throws CacheBillingException
     */
    public function testUnreadableHeader(): void
    {
        $handle = $this->tableToHandle([], $this->configs['tab-separated']);
        $sut = new CsvTableReader($handle, $this->configs['tab-separated']);

        $this->expectException(CacheBillingException::class);
        $sut->getHeader();

        $this->expectException(CacheBillingException::class);
        $sut->readRowAssoc();
    }

    /**
     * @return resource
     */
    private function tableToHandle(array $table, array $config)
    {
        $tempStream = fopen('php://temp', 'rw');
        foreach ($table as $row) {
            fputcsv($tempStream, $row, $config['separator'], $config['enclosure'], $config['escape']);
        }
        rewind($tempStream);

        return $tempStream;
    }
}

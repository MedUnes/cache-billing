<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Reader\Csv;

use App\Exception\CacheBillingException;
use App\Reader\TableReaderInterface;

class CsvTableReader implements TableReaderInterface
{
    /*
     * According to fgetcsv() documentation: (https://www.php.net/manual/en/function.fgetcsv.php):
     * `A blank line in a CSV file will be returned as an array comprising a single null field,
     *  and will not be treated as an error.`
     */
    private const BLANK_LINE = [0 => null];

    private array $header = [];
    private array $config;

    /** @var resource */
    private $handle;

    /**
     * @param resource $handle
     * @param string[] $config
     */
    public function __construct($handle, array $config)
    {
        $this->config = $config;
        $this->handle = $handle;
    }

    /**
     * @throws CacheBillingException
     */
    public function readRowAssoc(): ?array
    {
        $header = $this->getHeader();
        do {
            $row = $this->readRow();
        } while ($row === static::BLANK_LINE);

        if (empty($row)) {
            return null;
        }

        return array_combine($header, $row);
    }

    /**
     * @throws CacheBillingException
     *
     * @return string[]
     */
    public function getHeader(): array
    {
        if (empty($this->header)) {
            $header = $this->readRow();
            if (!$header) {
                throw new CacheBillingException('Unable to read header');
            }
            $this->header = $header;
        }

        return $this->header;
    }

    /**
     * @return array|false|null
     */
    protected function readRow()
    {
        return fgetcsv(
            $this->handle,
            0,
            $this->config['separator'],
            $this->config['enclosure'],
            $this->config['escape']
        );
    }
}

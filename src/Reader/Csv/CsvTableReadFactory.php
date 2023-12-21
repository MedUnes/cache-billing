<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Reader\Csv;

/**
 * @codeCoverageIgnore
 */
class CsvTableReadFactory
{
    /**
     * Although a nothing but a dumb new() call, let's stick to the separation of the creation from the usage of objects.
     *
     * @param mixed $handle
     */
    public static function create($handle, array $config): CsvTableReader
    {
        return new CsvTableReader($handle, $config);
    }
}

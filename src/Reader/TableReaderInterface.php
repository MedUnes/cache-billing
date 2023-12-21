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

namespace App\Reader;

/**
 * The implementation should make it possible to sequentially read an "abstract" source of data as table (header + rows).
 *
 * @codeCoverageIgnore
 */
interface TableReaderInterface
{
    /**
     * Will return the header of the table  (first row/line holding the names of the "columns")
     * The calls on this method are idempotent.
     *
     * @return string[]
     */
    public function getHeader(): array;

    /**
     * Reads one line/row of the table.
     * Each call will move the "cursor" and read the next row
     * Once null is returned, it means the reader reached the end of the table and nothing more can be expected.
     *
     * @return string[]|null
     */
    public function readRowAssoc(): ?array;
}

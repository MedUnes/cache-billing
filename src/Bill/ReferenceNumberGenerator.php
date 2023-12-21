<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Bill;

class ReferenceNumberGenerator
{
    /**
     * This tiny line is worth a class IMHO because the generation strategy might evolve to something more complex:
     * Ex: A remote/separated service provides a key,
     * or even simpler, reading the last key from a persisted resource (DB, file..).
     *
     * Simplest way to have a "production" key is to write the last generated number in a file,
     * so that it can be used in the next generation through auto_inc for ex.
     *
     * (public/export/bill.csv can be taken as en example to store the history of bills)
     */
    public function generate(string $year, string $billNumber): string
    {
        return sprintf('%s-M%s', $year, $billNumber);
    }
}

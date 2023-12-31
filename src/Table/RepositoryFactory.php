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

namespace App\Table;

/** @codeCoverageIgnore  */
class RepositoryFactory
{
    public static function create(array $data): Repository
    {
        return new Repository($data);
    }
}

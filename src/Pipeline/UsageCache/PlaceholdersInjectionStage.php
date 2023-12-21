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

namespace App\Pipeline\UsageCache;

use App\Exception\CacheBillingException;

/** @codeCoverageIgnore */
class PlaceholdersInjectionStage
{
    /** @var string[] */
    private array $usageCacheHeaderMap;

    public function __construct(array $usageCacheHeaderMap)
    {
        $this->usageCacheHeaderMap = $usageCacheHeaderMap;
    }

    /**
     * @throws CacheBillingException
     */
    public function __invoke(Payload $payload): Payload
    {
        $payload->setHeaderMap($this->usageCacheHeaderMap);

        return $payload;
    }
}

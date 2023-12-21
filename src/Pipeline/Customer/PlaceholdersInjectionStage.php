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

namespace App\Pipeline\Customer;

use App\Exception\CacheBillingException;

/** @codeCoverageIgnore */
class PlaceholdersInjectionStage
{
    /** @var string[] */
    private array $customerDataHeaderMap;

    public function __construct(array $customerDataHeaderMap)
    {
        $this->customerDataHeaderMap = $customerDataHeaderMap;
    }

    /**
     * @throws CacheBillingException
     */
    public function __invoke(Payload $payload): Payload
    {
        $payload->setHeaderMap($this->customerDataHeaderMap);

        return $payload;
    }
}

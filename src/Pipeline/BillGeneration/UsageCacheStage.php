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

namespace App\Pipeline\BillGeneration;

use App\Pipeline\UsageCache\Payload as UsageCachePayload;
use League\Pipeline\Pipeline;

/** @codeCoverageIgnore */
class UsageCacheStage
{
    private Pipeline $usageCachePipeline;

    public function __construct(Pipeline $usageCachePipeline)
    {
        $this->usageCachePipeline = $usageCachePipeline;
    }

    public function __invoke(Payload $payload): Payload
    {
        $usageCachePayload = new UsageCachePayload();
        $usageCachePayload
            ->setBillMonth($payload->getBillMonth())
            ->setBillYear($payload->getBillYear())
            ->setUsageCacheFileNames($payload->getUsageCacheFileNames())
            ->setDomain($payload->getCustomerPayload()->getCustomer()['domain'])
        ;

        /** @var UsageCachePayload $usageCachePayload */
        $usageCachePayload = $this->usageCachePipeline->process($usageCachePayload);

        $payload->setUsageCachePayload($usageCachePayload);

        return $payload;
    }
}

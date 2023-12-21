<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Pipeline\UsageCache;

use App\Exception\CacheBillingException;
use App\Table\RepositoryFactory;

/** @codeCoverageIgnore */
class AggregationStage
{
    private const DAYS_OF_MONTH_PATTERN = '(0[1-9]|^[12]\d|^3[01])';

    /**
     * @throws CacheBillingException
     */
    public function __invoke(Payload $payload): Payload
    {
        $dataUsageRepository = RepositoryFactory::create($payload->getProcessedData());

        $totalPi = (int) $dataUsageRepository->sum(
            [
                'domain' => preg_quote($payload->getDomain()),
                'date' => sprintf('^%s\.%s\.%s$', self::DAYS_OF_MONTH_PATTERN, $payload->getBillMonth(), $payload->getBillYear()),
            ],
            'pi'
        );

        $payload->setTotalPi($totalPi);

        return $payload;
    }
}

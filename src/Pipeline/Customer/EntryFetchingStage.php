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
use App\Table\RepositoryFactory;

/** @codeCoverageIgnore */
class EntryFetchingStage
{
    /**
     * @throws CacheBillingException
     */
    public function __invoke(Payload $payload): Payload
    {
        $customerDataRepository = RepositoryFactory::create($payload->getProcessedData());
        if (!$customer = $customerDataRepository->findOneBy(['username' => $payload->getUsername()])) {
            throw new \InvalidArgumentException(sprintf('Can not generate bill for unknown customer: %s', $payload->getUsername()));
        }

        $payload->setCustomer($customer);

        return $payload;
    }
}

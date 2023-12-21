<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Pipeline\BillGeneration;

use App\Pipeline\Customer\Payload as CustomerPayload;
use League\Pipeline\Pipeline;

/** @codeCoverageIgnore */
class CustomerStage
{
    private Pipeline $customerPipeline;

    public function __construct(Pipeline $customerPipeline)
    {
        $this->customerPipeline = $customerPipeline;
    }

    public function __invoke(Payload $payload): Payload
    {
        $customerPayload = new CustomerPayload();
        $customerPayload->setUsername($payload->getUsername());

        /** @var CustomerPayload $customerPayload */
        $customerPayload = $this->customerPipeline->process($customerPayload);

        $payload->setCustomerPayload($customerPayload);

        return $payload;
    }
}

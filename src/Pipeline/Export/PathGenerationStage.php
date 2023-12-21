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

namespace App\Pipeline\Export;

use App\Exception\CacheBillingException;

/** @codeCoverageIgnore */
class PathGenerationStage
{
    private string $exportPath;

    public function __construct(string $exportPath)
    {
        $this->exportPath = $exportPath;
    }

    /**
     * @throws CacheBillingException
     */
    public function __invoke(Payload $payload): Payload
    {
        $payload->setFullPath(sprintf(
            '%s/%s/%s/%s',
            $this->exportPath,
            $payload->getCustomer()['username'],
            $payload->getBillYear(),
            $payload->getBillMonth()
        ));

        return $payload;
    }
}

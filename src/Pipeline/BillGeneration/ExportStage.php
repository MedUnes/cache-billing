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

use App\Pipeline\Export\Payload as ExportPayload;
use League\Pipeline\Pipeline;

/** @codeCoverageIgnore */
class ExportStage
{
    private Pipeline $exportPipeline;

    public function __construct(Pipeline $exportPipeline)
    {
        $this->exportPipeline = $exportPipeline;
    }

    public function __invoke(Payload $payload): Payload
    {
        $exportPayload = new ExportPayload();
        $exportPayload
            ->setExportTypes($payload->getExportTypes())
            ->setBillYear($payload->getUsageCachePayload()->getBillYear())
            ->setBillMonth($payload->getBillMonth())
            ->setCustomer($payload->getCustomerPayload()->getCustomer())
            ->setTotalPi($payload->getUsageCachePayload()->getTotalPi())
        ;

        /** @var ExportPayload $exportPayload */
        $exportPayload = $this->exportPipeline->process($exportPayload);
        $payload->setFullFilePaths($exportPayload->getFullFilePaths());

        return $payload;
    }
}

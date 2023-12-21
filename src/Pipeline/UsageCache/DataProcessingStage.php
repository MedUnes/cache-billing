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
use App\Pipeline\DataProcessingStage as BaseDataProcessingStage;
use App\Reader\Csv\CsvTableReadFactory;

/** @codeCoverageIgnore */
class DataProcessingStage
{
    /** @var string[] */
    private array $usageCacheFileConfig;
    private BaseDataProcessingStage $dataProcessingStage;

    public function __construct(BaseDataProcessingStage $dataProcessingStage, array $usageCacheFileConfig)
    {
        $this->usageCacheFileConfig = $usageCacheFileConfig;
        $this->dataProcessingStage = $dataProcessingStage;
    }

    /**
     * @throws CacheBillingException
     */
    public function __invoke(Payload $payload): Payload
    {
        $usageCacheData = [];
        foreach ($payload->getFinder() as $file) {
            $fileName = $file->getRealPath();
            $handle = fopen($fileName, 'r');
            $payload->setTableReader(CsvTableReadFactory::create($handle, $this->usageCacheFileConfig));
            $processedBasePayload = $this->dataProcessingStage->__invoke($payload);
            fclose($handle);
            $usageCacheData = array_merge($usageCacheData, $processedBasePayload->getProcessedData());
        }
        $payload->setProcessedData($usageCacheData);

        return $payload;
    }
}

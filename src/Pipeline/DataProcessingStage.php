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

namespace App\Pipeline;

use App\Table\Row\Mapper;
use App\Table\Row\Validator;

/** @codeCoverageIgnore  */
class DataProcessingStage
{
    private Validator $rowValidator;
    private Mapper $mapper;

    public function __construct(Validator $rowValidator, Mapper $mapper)
    {
        $this->rowValidator = $rowValidator;
        $this->mapper = $mapper;
    }

    public function __invoke(BasePayload $payload): BasePayload
    {
        $processedData = [];
        while ($row = $payload->getTableReader()->readRowAssoc()) {
            if (!$this->rowValidator->hasRequiredFields($row, $payload->getHeaderMap())) {
                continue;
            }
            $mappedRow = $this->mapper->map($row, $payload->getHeaderMap());
            $processedData[] = $mappedRow;
        }

        $payload->setProcessedData($processedData);

        return $payload;
    }
}

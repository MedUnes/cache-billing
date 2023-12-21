<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Table\Row;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Validator implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @param string[] $row
     * @param string[] $headerMap
     */
    public function hasRequiredFields(array $row, array $headerMap, ?array $requiredFields = null): bool
    {
        $requiredFields = $requiredFields ?? array_keys($headerMap);
        $rowFields = array_keys($row);
        $missingFields = array_diff($requiredFields, $rowFields);

        if (!empty($missingFields)) {
            $this->logger->warning(
                'Unable to map row, missing fields: {missingFields}', [
                'missingFields' => $missingFields,
            ]);

            return false;
        }

        return true;
    }
}

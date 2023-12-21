<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Table;

use App\Exception\CacheBillingException;

/**
 * A class for simple operations on a table like aggregation and filtering.
 *
 * Somehow mimics the known ORM Repositories :)
 */
class Repository
{
    /**
     * The data must be in a form of a named table, sequence of rows on which queries and aggregations can operate.
     */
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns the sum of the column: $groupBy and under the conditions: $criteria.
     *
     * @param array $criteria An array of columnName => regexp to be taken as condition while aggregating the rows
     *
     * @throws CacheBillingException
     *
     * @return float The aggregated sum as a float (more generic than int, huh ?)
     */
    public function sum(array $criteria, string $groupBy): float
    {
        $sum = 0.0;

        if (empty($this->data)) {
            return 0.0;
        }

        $this->validateCriteria(array_merge($criteria, [$groupBy => $groupBy]));
        foreach ($this->data as $row) {
            if ($this->meetsCriteria($row, $criteria)) {
                $sum += $row[$groupBy];
            }
        }

        return $sum;
    }

    /**
     * Fetches one row from the table/data that meets the given criteria
     * If more than one are found, the first occurrence will be considered
     * No orderBy implemented till now, the order as given in the data property is what counts.
     *
     * @throws CacheBillingException
     */
    public function findOneBy(array $criteria): ?array
    {
        if (empty($this->data)) {
            return null;
        }

        $this->validateCriteria($criteria);
        foreach ($this->data as $row) {
            if ($this->meetsCriteria($row, $criteria)) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @param string[] $criteria
     *
     * @throws CacheBillingException
     */
    private function validateCriteria(array $criteria): void
    {
        $fieldNames = array_keys($this->data[0]);
        foreach ($criteria as $key => $value) {
            if (!\in_array($key, $fieldNames, true)) {
                throw new CacheBillingException(sprintf('Unknown field: %s', $key));
            }
        }
    }

    /**
     * @param string[] $row
     * @param string[] $criteria
     */
    private function meetsCriteria(array $row, array $criteria): bool
    {
        foreach ($criteria as $field => $rule) {
            if (!preg_match("/{$rule}/", $row[$field])) {
                return false;
            }
        }

        return true;
    }
}

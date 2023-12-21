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

namespace App\Table\Row;

class Mapper
{
    /**
     * Given a row and column names map, returns a mapped row.
     * Ex:  $row = ["name" => "John Doe", "birth_date" => "01.01.1983"]
     *      $headerMap = ["name" => "Name", "birth_date" => "Geburtsdatum"]
     *      will return  ["Name" => "John Doe", "Geburtsdatum" => "01.01.1983"].
     *
     * @param string[] $row       a key/val array or columnName/value to be mapped
     * @param string[] $headerMap a key/val array where the keys are the source column names and the values
     *                            are the destination column names. Ex: ["name"=>"Name", "birth_date"=>"Geburtsdatum"]
     *
     * @return string[]
     */
    public function map(array $row, array $headerMap): array
    {
        $mappedRow = [];
        foreach ($row as $key => $value) {
            $mappedRow[$headerMap[$key]] = $value;
        }

        return $mappedRow;
    }
}

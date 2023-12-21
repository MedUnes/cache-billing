<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Pipeline\Export;

use App\Bill\CalculatorFactory;

/** @codeCoverageIgnore */
class PlaceholderMapStage
{
    private string $exportPath;

    public function __construct(string $exportPath)
    {
        $this->exportPath = $exportPath;
    }

    public function __invoke(Payload $payload): Payload
    {
        $customer = $payload->getCustomer();
        $calculator = CalculatorFactory::create((float) $customer['unit_price'], (float) $customer['vat']);
        $totalPi = $payload->getTotalPi();
        $billMonth = $payload->getBillMonth();
        $billYear = $payload->getBillYear();
        $placeholderMap = array_merge(
            $customer,
            [
                'edit_date' => date('d.m.Y'),
                'total_amount' => $calculator->total($totalPi),
                'month' => date('M Y', strtotime("01.$billMonth.$billYear")),
                'vat' => $calculator->vat($totalPi),
                'net_amount' => $calculator->cost($totalPi),
                'units' => $calculator->units($totalPi),
                'reference_number' => $payload->getReferenceNumber(),
            ]
        );

        $payload->setPlaceholderMap($placeholderMap);

        return $payload;
    }
}

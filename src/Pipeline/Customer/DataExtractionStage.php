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
use App\OpenOffice\Extractor;
use App\Reader\Xml\XmlTableReaderFactory;

/** @codeCoverageIgnore */
class DataExtractionStage
{
    private Extractor $openOfficeDataExtractor;
    private string $customerDataFilePath;
    private string $customerDataFileEntryXpath;

    public function __construct(Extractor $openOfficeDataExtractor, string $customerDataFilePath, string $customerDataFileEntryXpath)
    {
        $this->openOfficeDataExtractor = $openOfficeDataExtractor;
        $this->customerDataFilePath = $customerDataFilePath;
        $this->customerDataFileEntryXpath = $customerDataFileEntryXpath;
    }

    /**
     * @throws CacheBillingException
     */
    public function __invoke(Payload $payload): Payload
    {
        $xmlCustomerData = $this->openOfficeDataExtractor->extractXml($this->customerDataFilePath);
        $xmlTableReader = XmlTableReaderFactory::create($xmlCustomerData, $this->customerDataFileEntryXpath);

        $payload->setTableReader($xmlTableReader);

        return $payload;
    }
}

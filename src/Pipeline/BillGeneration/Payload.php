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

use App\Pipeline\Customer\Payload as CustomerPayload;
use App\Pipeline\Export\Payload as ExportPayload;
use App\Pipeline\UsageCache\Payload as UsageCachePayload;

/** @codeCoverageIgnore */
class Payload
{
    /** @var string[] */
    private array $exportTypes;
    private string $billYear;
    private string $billMonth;
    private string $username;
    private array $usageCacheFileNames;
    private array $fullFilePaths = [];

    private CustomerPayload $customerPayload;
    private UsageCachePayload $usageCachePayload;
    private ExportPayload $exportPayload;

    /**
     * @return string[]
     */
    public function getExportTypes(): array
    {
        return $this->exportTypes;
    }

    /**
     * @param string[] $exportTypes
     */
    public function setExportTypes(array $exportTypes): self
    {
        $this->exportTypes = $exportTypes;

        return $this;
    }

    public function getBillYear(): string
    {
        return $this->billYear;
    }

    public function setBillYear(string $billYear): self
    {
        $this->billYear = $billYear;

        return $this;
    }

    public function getBillMonth(): string
    {
        return $this->billMonth;
    }

    public function setBillMonth(string $billMonth): self
    {
        $this->billMonth = $billMonth;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUsageCacheFileNames(): array
    {
        return $this->usageCacheFileNames;
    }

    public function setUsageCacheFileNames(array $usageCacheFileNames): self
    {
        $this->usageCacheFileNames = $usageCacheFileNames;

        return $this;
    }

    public function getFullFilePaths(): array
    {
        return $this->fullFilePaths;
    }

    public function setFullFilePaths(array $fullFilePaths): self
    {
        $this->fullFilePaths = $fullFilePaths;

        return $this;
    }

    public function getCustomerPayload(): CustomerPayload
    {
        return $this->customerPayload;
    }

    public function setCustomerPayload(CustomerPayload $customerPayload): self
    {
        $this->customerPayload = $customerPayload;

        return $this;
    }

    public function getUsageCachePayload(): UsageCachePayload
    {
        return $this->usageCachePayload;
    }

    public function setUsageCachePayload(UsageCachePayload $usageCachePayload): self
    {
        $this->usageCachePayload = $usageCachePayload;

        return $this;
    }

    public function getExportPayload(): ExportPayload
    {
        return $this->exportPayload;
    }

    public function setExportPayload(ExportPayload $exportPayload): self
    {
        $this->exportPayload = $exportPayload;

        return $this;
    }
}

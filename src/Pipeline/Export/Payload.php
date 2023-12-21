<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Pipeline\Export;

/** @codeCoverageIgnore */
class Payload
{
    /** @var string[] */
    private array $exportTypes;
    private string $billYear;
    private string $billMonth;
    private array $customer;
    private string $fullPath;
    private int $totalPi;

    private string $referenceNumber;
    private array $placeholderMap;
    /** @var string[] */
    private array $fullFilePaths = [];

    public function getReferenceNumber(): string
    {
        return $this->referenceNumber;
    }

    public function setReferenceNumber(string $referenceNumber): self
    {
        $this->referenceNumber = $referenceNumber;

        return $this;
    }

    public function getPlaceholderMap(): array
    {
        return $this->placeholderMap;
    }

    public function setPlaceholderMap(array $placeholderMap): self
    {
        $this->placeholderMap = $placeholderMap;

        return $this;
    }

    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    public function setFullPath(string $fullPath): self
    {
        $this->fullPath = $fullPath;

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

    public function getTotalPi(): int
    {
        return $this->totalPi;
    }

    public function setTotalPi(int $totalPi): self
    {
        $this->totalPi = $totalPi;

        return $this;
    }

    public function getCustomer(): array
    {
        return $this->customer;
    }

    public function setCustomer(array $customer): self
    {
        $this->customer = $customer;

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

    public function getExportTypes(): array
    {
        return $this->exportTypes;
    }

    public function setExportTypes(array $exportTypes): self
    {
        $this->exportTypes = $exportTypes;

        return $this;
    }
}

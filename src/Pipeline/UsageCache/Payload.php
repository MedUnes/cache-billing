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

use App\Pipeline\BasePayload;
use Symfony\Component\Finder\Finder;

/** @codeCoverageIgnore  */
class Payload extends BasePayload
{
    protected string $billYear;
    protected string $billMonth;
    protected Finder $finder;
    private array $usageCacheFileNames;
    private string $domain;
    private int $totalPi;

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

    public function getUsageCacheFileNames(): array
    {
        return $this->usageCacheFileNames;
    }

    public function setUsageCacheFileNames(array $usageCacheFileNames): self
    {
        $this->usageCacheFileNames = $usageCacheFileNames;

        return $this;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

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

    public function getFinder(): Finder
    {
        return $this->finder;
    }

    public function setFinder(Finder $finder): self
    {
        $this->finder = $finder;

        return $this;
    }
}

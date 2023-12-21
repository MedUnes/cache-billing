<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Pipeline\UsageCache;

use App\Exception\CacheBillingException;
use Symfony\Component\Finder\Finder;

/** @codeCoverageIgnore */
class FilesLoadingStage
{
    private string $usageCacheFilePath;

    public function __construct(string $usageCacheFilePath)
    {
        $this->usageCacheFilePath = $usageCacheFilePath;
    }

    /**
     * @throws CacheBillingException
     */
    public function __invoke(Payload $payload): Payload
    {
        $finder = new Finder();
        $finder
            ->in($this->usageCacheFilePath)
            ->files()
            ->name($payload->getUsageCacheFileNames());
        if (!$finder->hasResults()) {
            throw new CacheBillingException(sprintf('No usage cache files with the patterns: %s found in %s', implode(',', $payload->getUsageCacheFileNames()), $this->usageCacheFilePath));
        }
        $payload->setFinder($finder);

        return $payload;
    }
}

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

namespace App\Pipeline\Export;

use App\Exception\CacheBillingException;

/** @codeCoverageIgnore */
class PathCreationStage
{
    /**
     * @throws CacheBillingException
     */
    public function __invoke(Payload $payload): Payload
    {
        $fullExportPath = $payload->getFullPath();
        exec("mkdir -p {$fullExportPath}", $output, $result);
        if ($result) {
            throw new CacheBillingException(sprintf('Unable create export file path: %s', $fullExportPath));
        }

        return $payload;
    }
}

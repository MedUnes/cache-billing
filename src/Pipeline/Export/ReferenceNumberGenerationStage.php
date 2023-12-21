<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Pipeline\Export;

use App\Bill\ReferenceNumberGenerator;

/** @codeCoverageIgnore */
class ReferenceNumberGenerationStage
{
    private string $exportPath;
    private ReferenceNumberGenerator $referenceNumberGenerator;

    public function __construct(string $exportPath, ReferenceNumberGenerator $referenceNumberGenerator)
    {
        $this->exportPath = $exportPath;
        $this->referenceNumberGenerator = $referenceNumberGenerator;
    }

    public function __invoke(Payload $payload): Payload
    {
        $payload->setReferenceNumber($this->referenceNumberGenerator->generate(date('Y'), '1234'));

        return $payload;
    }
}

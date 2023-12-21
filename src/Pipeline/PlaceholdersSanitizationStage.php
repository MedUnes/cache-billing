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

namespace App\Pipeline;

use App\Table\Row\PlaceHolderMapSanitizer;

/** @codeCoverageIgnore */
class PlaceholdersSanitizationStage
{
    private PlaceHolderMapSanitizer $placeHolderMapSanitizer;

    /** @var string[] */
    private array $enclosureMap;

    public function __construct(PlaceHolderMapSanitizer $placeHolderMapSanitizer, array $enclosureMap)
    {
        $this->placeHolderMapSanitizer = $placeHolderMapSanitizer;
        $this->enclosureMap = $enclosureMap;
    }

    public function __invoke(BasePayload $payload): BasePayload
    {
        $payload->setHeaderMap($this->placeHolderMapSanitizer->sanitize($payload->getHeaderMap(), $this->enclosureMap));

        return $payload;
    }
}

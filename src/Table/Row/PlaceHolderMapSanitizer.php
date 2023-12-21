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

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

/**
 * Workaround to wipe-out the double percentage sign surrounding parameter names, although SF doc claims to be fine..
 * https://symfony.com/doc/current/configuration.html#configuration-parameters.
 */
class PlaceHolderMapSanitizer implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function sanitize(array $placeHolderMap, array $enclosureMap): array
    {
        $appEnclosure = key($enclosureMap);
        $templateEnclosure = reset($enclosureMap);

        return array_flip(str_replace($appEnclosure, $templateEnclosure, array_flip($placeHolderMap)));
    }
}

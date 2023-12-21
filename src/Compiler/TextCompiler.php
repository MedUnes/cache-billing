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

namespace App\Compiler;

/**
 * Despite the name, this is the dumbest compiler ever, that just replaces occurrences of placeholders with their
 * corresponding real values, based on a given map.
 */
class TextCompiler
{
    public function compile(string $templateText, array $placeholderMap): string
    {
        $compiledText = $templateText;
        foreach ($placeholderMap as $placeholder => $realValue) {
            $pattern = sprintf('/%s/', preg_quote($placeholder));
            $compiledText = preg_replace($pattern, (string) $realValue, $compiledText);
        }

        return $compiledText;
    }
}

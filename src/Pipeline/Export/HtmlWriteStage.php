<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Pipeline\Export;

use Twig\Environment as Twig;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/** @codeCoverageIgnore */
class HtmlWriteStage
{
    private const HTML_EXTENSION = 'html';
    private string $exportPath;
    private Twig $twig;

    public function __construct(Twig $twig, string $exportPath)
    {
        $this->exportPath = $exportPath;
        $this->twig = $twig;
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function __invoke(Payload $payload): Payload
    {
        if (!\in_array(self::HTML_EXTENSION, $payload->getExportTypes(), true)) {
            return $payload;
        }
        $htmlBill = $this->twig->render('bill.html.twig', $payload->getPlaceholderMap());
        $fullFilePath = sprintf('%s/%s.%s', $payload->getFullPath(), $payload->getReferenceNumber(), self::HTML_EXTENSION);
        $payload->setFullFilePaths(array_merge($payload->getFullFilePaths(), [$fullFilePath]));
        file_put_contents($fullFilePath, $htmlBill);

        return $payload;
    }
}

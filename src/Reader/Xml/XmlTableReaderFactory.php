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

namespace App\Reader\Xml;

use Symfony\Component\DomCrawler\Crawler;

/** @codeCoverageIgnore  */
class XmlTableReaderFactory
{
    public static function create(string $xmlData, string $initialXpath): XmlTableReader
    {
        $crawler = new Crawler($xmlData);
        $crawler = $crawler->filterXPath($initialXpath);

        return new XmlTableReader($crawler);
    }
}

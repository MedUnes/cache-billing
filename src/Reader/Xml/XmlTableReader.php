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

use App\Exception\CacheBillingException;
use App\Reader\TableReaderInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * This is not a Generic XML as a table reader, but rather a precise subcase of it.
 *
 * This reader targets a Table inside an ODT file  (download/template/Template_Bill.odt)
 */
class XmlTableReader implements TableReaderInterface
{
    private array $header = [];
    private Crawler $crawler;

    public function __construct(Crawler $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @throws CacheBillingException
     *
     * @return string[]
     */
    public function getHeader(): array
    {
        if (empty($this->header)) {
            $header = $this->readHeaderRow();
            if (!$header) {
                throw new CacheBillingException('Unable to read header');
            }
            $this->header = $header;
        }

        return $this->header;
    }

    /**
     * @throws CacheBillingException
     *
     * @return string[]|null
     */
    public function readRowAssoc(): ?array
    {
        $header = $this->getHeader();
        $row = $this->readRow();

        if (empty($row)) {
            return null;
        }

        return array_combine($header, $row);
    }

    /**
     * @psalm-suppress UndefinedVariable
     *
     * @throws CacheBillingException
     */
    private function readRow(): ?array
    {
        if ($this->crawler->count() === 0) {
            return null;
        }

        $row = [];
        $tableRow = $this->crawler->first();
        /** @var Crawler[] $cellNodes */
        $cellNodes = $tableRow->filterXPath('//table:table-cell')->each(function ($node) {
            return $node;
        });
        foreach ($cellNodes as $cellNode) {
            $duplications = $cellNode->attr('table:number-columns-repeated') ?: 1;
            for ($i = 0; $i < $duplications && \count($row) < \count($this->getHeader()); ++$i) {
                $row[] = $this->readCell($cellNode);
            }
        }

        $this->crawler = $this->crawler->slice(1);

        return empty($row) ? null : $row;
    }

    private function readHeaderRow(): ?array
    {
        if ($this->crawler->count() === 0) {
            return null;
        }

        $header = $this
            ->crawler
            ->first()
            ->filterXPath('//table:table-cell/text:p')
            ->each(function ($node) {
                return $node->text();
            }) ?: null;
        $this->crawler = $this->crawler->slice(1);

        return $header;
    }

    private function readCell(Crawler $cellNode): string
    {
        $textCell = $cellNode->filterXPath('//text:p');

        return $textCell->count() === 0 ? '' : $cellNode->filterXPath('//text:p')->text();
    }
}

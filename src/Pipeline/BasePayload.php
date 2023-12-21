<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Pipeline;

use App\Reader\TableReaderInterface;

/**
 * For a better understanding of the Pipeline pattern and use-cases, this blogpost can be a nice one:
 * https://medium.com/@agoalofalife/pipeline-and-php-d9bb0a6370ca.
 *
 * And the small documentation of the application comes with a diagram which tries to explain how the data
 * flows through multiple pipelines and over different payloads to end up with the exported bill. (doc/diagram.png)
 *
 * @codeCoverageIgnore
 */
class BasePayload
{
    /** @var string[] */
    protected array $enclosureMap;

    /** @var string[] */
    protected array $headerMap;

    protected array $processedData;
    protected TableReaderInterface $tableReader;

    public function getEnclosureMap(): array
    {
        return $this->enclosureMap;
    }

    public function setEnclosureMap(array $enclosureMap): self
    {
        $this->enclosureMap = $enclosureMap;

        return $this;
    }

    public function getHeaderMap(): array
    {
        return $this->headerMap;
    }

    public function setHeaderMap(array $headerMap): self
    {
        $this->headerMap = $headerMap;

        return $this;
    }

    public function getProcessedData(): array
    {
        return $this->processedData;
    }

    public function setProcessedData(array $processedData): self
    {
        $this->processedData = $processedData;

        return $this;
    }

    public function getTableReader(): TableReaderInterface
    {
        return $this->tableReader;
    }

    public function setTableReader(TableReaderInterface $tableReader): self
    {
        $this->tableReader = $tableReader;

        return $this;
    }
}

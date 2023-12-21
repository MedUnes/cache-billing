<?php

declare(strict_types=1);

/*
 * This file is part of the medunes/cache-billing PHP package.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\OpenOffice;

use App\Exception\CacheBillingException;

/**
 * As described in the Extractor class, this class does the opposite:
 * Given an XML content, and an OpenOfficeFile, it unzips, replaces the content zips again and clears the tmp folder.
 *
 * @codeCoverageIgnore
 * Yet, can be subject to integration or functional tests
 */
class Injector
{
    private const CONTENT_FILE_NAME = 'content.xml';
    private string $tmpPath;

    public function __construct(string $tmpPath)
    {
        $this->tmpPath = $tmpPath;
    }

    /**
     * @throws CacheBillingException
     */
    public function injectXml(string $filePath, string $xmlData, string $exportPath): void
    {
        $fullTmpPath = sprintf('%s/%s/', $this->tmpPath, md5((string) microtime(true)));

        $this->unpackOpenOfficeDocument($filePath, $fullTmpPath);
        $this->overwriteContent($xmlData, $fullTmpPath);
        $this->createIfNotExistsExportPath($fullTmpPath);
        $this->packOpenOfficeDocument($exportPath, $fullTmpPath);
        $this->clear($fullTmpPath);
    }

    /**
     * @throws CacheBillingException
     */
    private function unpackOpenOfficeDocument(string $filePath, string $fullTmpPath): void
    {
        $explodedPath = explode('/', $filePath);
        $shortFilePath = end($explodedPath);
        $cmd = "mkdir $fullTmpPath && cp $filePath $fullTmpPath && cd $fullTmpPath && unzip $shortFilePath && rm $shortFilePath";
        exec($cmd, $output, $result);

        if ($result) {
            throw new CacheBillingException(sprintf('Unable unpack user data file: %s', $filePath));
        }
    }

    /**
     * @throws CacheBillingException
     */
    private function packOpenOfficeDocument(string $filePath, string $fullTmpPath): void
    {
        $cmd = "cd $fullTmpPath && zip -r - . | dd of=$filePath ";
        exec($cmd, $output, $result);

        if ($result) {
            throw new CacheBillingException(sprintf('Unable to generate Bill for %s (ODT/zip failure)', $filePath));
        }
    }

    /**
     * @throws CacheBillingException
     */
    private function overwriteContent(string $xmlData, string $fullTmpPath): void
    {
        if (!file_put_contents($fullTmpPath.static::CONTENT_FILE_NAME, $xmlData)) {
            throw new CacheBillingException(sprintf('Unable to write content to Bill ODT file: %s', $fullTmpPath.static::CONTENT_FILE_NAME));
        }
    }

    private function clear(string $fullTmpPath): void
    {
        @exec("rm -rf $fullTmpPath");
    }

    /**
     * @throws CacheBillingException
     */
    private function createIfNotExistsExportPath(string $exportPath)
    {
        $cmd = "mkdir -p $exportPath";
        exec($cmd, $output, $result);

        if ($result) {
            throw new CacheBillingException("Unable to create Bill export path  $exportPath");
        }
    }
}

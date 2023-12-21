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

namespace App\OpenOffice;

use App\Exception\CacheBillingException;

/**
 * The responsibility of this class is to unzip, read the content.xml (raw content) and clear the tmp folder.
 *
 * According to this official documentation: https://help.libreoffice.org/3.3/Common/XML_File_Formats
 * Documents in OpenDocument file format are stored as compressed zip archives that contain XML files.
 * To view these XML files, you can open the OpenDocument file with an unzip program.
 * The following files and directories are contained within the OpenDocument files:
 * 1. The text content of the document is located in content.xml
 * ..
 *
 * @codeCoverageIgnore
 * Yet, can be subject to integration or functional tests
 */
class Extractor
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
    public function extractXml(string $filePath): string
    {
        $fullTmpPath = sprintf('%s/%s/', $this->tmpPath, md5((string) microtime(true)));

        $this->unpackOpenOfficeDocument($filePath, $fullTmpPath);
        $userData = $this->readContent($fullTmpPath, $filePath);
        $this->clear($fullTmpPath);

        return $userData;
    }

    /**
     * @throws CacheBillingException
     */
    private function unpackOpenOfficeDocument(string $filePath, string $fullTmpPath): void
    {
        exec("unzip -o {$filePath} -d {$fullTmpPath}", $output, $result);
        if ($result) {
            throw new CacheBillingException(sprintf('Unable unpack user data file: %s', $filePath));
        }
    }

    /**
     * @throws CacheBillingException
     */
    private function readContent(string $fullTmpPath, string $filePath): string
    {
        if (!$userData = file_get_contents($fullTmpPath.self::CONTENT_FILE_NAME)) {
            throw new CacheBillingException(sprintf('Unable to load customer data from: %s', $filePath));
        }

        return $userData;
    }

    private function clear(string $fullTmpPath): void
    {
        @exec("rm -rf {$fullTmpPath}");
    }
}

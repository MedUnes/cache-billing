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

namespace App\Pipeline\Export;

use App\Compiler\TextCompiler;
use App\Exception\CacheBillingException;
use App\OpenOffice\Extractor;
use App\OpenOffice\Injector;
use App\Table\Row\Mapper;
use App\Table\Row\PlaceHolderMapSanitizer;

/** @codeCoverageIgnore */
class OdtWriteStage
{
    private const ODT_EXTENSION = 'odt';

    private Extractor $openOfficeDataExtractor;
    private string $templateDataFilePath;
    private Injector $openOfficeDataInjector;
    private TextCompiler $templateCompiler;
    /** @var string[] */
    private array $customerDataHeaderMap;
    /** @var string[] */
    private array $templatePlaceholders;
    private Mapper $mapper;
    private PlaceHolderMapSanitizer $placeHolderMapSanitizer;
    private array $enclosureMap;

    /**
     * @param string[] $customerDataHeaderMap
     * @param string[] $templatePlaceholders
     */
    public function __construct(
        Extractor $openOfficeDataExtractor,
        string $templateDataFilePath,
        Injector $openOfficeDataInjector,
        TextCompiler $templateCompiler,
        array $customerDataHeaderMap,
        array $templatePlaceholders,
        array $enclosureMap,
        Mapper $mapper,
        PlaceHolderMapSanitizer $placeHolderMapSanitizer
    ) {
        $this->enclosureMap = $enclosureMap;
        $this->openOfficeDataExtractor = $openOfficeDataExtractor;
        $this->templateDataFilePath = $templateDataFilePath;
        $this->openOfficeDataInjector = $openOfficeDataInjector;
        $this->templateCompiler = $templateCompiler;
        $this->customerDataHeaderMap = $customerDataHeaderMap;
        $this->templatePlaceholders = $templatePlaceholders;
        $this->mapper = $mapper;
        $this->placeHolderMapSanitizer = $placeHolderMapSanitizer;
    }

    /**
     * @throws CacheBillingException
     */
    public function __invoke(Payload $payload): Payload
    {
        if (!\in_array(self::ODT_EXTENSION, $payload->getExportTypes(), true)) {
            return $payload;
        }
        $xmlTemplateData = $this->openOfficeDataExtractor->extractXml($this->templateDataFilePath);
        $odtPlaceholderMap = $this->buildPlaceholderMapOdt($payload->getPlaceholderMap());
        $compiledTemplate = $this->templateCompiler->compile($xmlTemplateData, $odtPlaceholderMap);
        $fullFilePath = sprintf('%s/%s.%s', $payload->getFullPath(), $payload->getReferenceNumber(), self::ODT_EXTENSION);
        $this->openOfficeDataInjector->injectXml($this->templateDataFilePath, $compiledTemplate, $fullFilePath);

        $payload->setFullFilePaths(array_merge($payload->getFullFilePaths(), [$fullFilePath]));

        return $payload;
    }

    private function buildPlaceholderMapOdt(array $canonicalPlaceholderMap): array
    {
        $sanitizedTemplatePlaceholders = $this->placeHolderMapSanitizer->sanitize($this->templatePlaceholders, $this->enclosureMap);
        $placeholderMap = array_flip(array_merge($this->customerDataHeaderMap, $sanitizedTemplatePlaceholders));

        return $this->mapper->map($canonicalPlaceholderMap, $placeholderMap);
    }
}

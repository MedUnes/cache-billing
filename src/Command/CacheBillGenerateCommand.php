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

namespace App\Command;

use App\Pipeline\BillGeneration\Payload;
use League\Pipeline\Pipeline;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/** @codeCoverageIgnore  */
class CacheBillGenerateCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private const EXPORT_TYPE_HTML = 'html';
    private const EXPORT_TYPE_ODT = 'odt';
    private const USAGE_CACHE_EXTENSION = 'csv';
    private const SupportExportTypes = [self::EXPORT_TYPE_HTML, self::EXPORT_TYPE_ODT];
    protected static $defaultName = 'cache:bill:generate';
    protected static $defaultDescription = 'Generates customer bill given its username and usage year/month';

    private Pipeline $billGenerationPipeline;

    public function __construct(Pipeline $billGenerationPipeline)
    {
        parent::__construct();

        $this->billGenerationPipeline = $billGenerationPipeline;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('username', InputArgument::REQUIRED, 'Customer username')
            ->addOption(
                'year',
                'y',
                InputOption::VALUE_OPTIONAL,
                'Usage year',
                date('Y')
            )
            ->addOption(
                'month',
                'm',
                InputOption::VALUE_OPTIONAL,
                'Usage month',
                date('m')
            )
            ->addOption(
                'files',
                'f',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Usage cache file names (csv) to be scanned (default: all)',
                ['*.'.self::USAGE_CACHE_EXTENSION]
            )->addOption(
                'export',
                'x',
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                "Generates bill file type: one of 'odt' or 'html'",
                self::SupportExportTypes
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $username */
        $username = $input->getArgument('username');

        /** @var string[] $usageCacheFileNames */
        $usageCacheFileNames = $input->getOption('files');

        /** @var string $billYear */
        $billYear = $input->getOption('year');

        /** @var string $billMonth */
        $billMonth = $input->getOption('month');

        /** @var array $exportTypes */
        $exportTypes = $input->getOption('export');

        try {
            $this->validateYear($billYear);
            $this->validateMonth($billMonth);
            $this->validateUsageFileNames($usageCacheFileNames);
            $this->validateExportTypes($exportTypes);

            $payload = new Payload();
            $payload
                ->setUsername($username)
                ->setBillMonth($billMonth)
                ->setBillYear($billYear)
                ->setExportTypes($exportTypes)
                ->setUsageCacheFileNames($usageCacheFileNames)
            ;

            /** @var Payload $processedPayload */
            $processedPayload = $this->billGenerationPipeline->process($payload);

            $this->logger->info('Successfully generated Cache Bill', [
                'customer' => $username,
                'billMonth' => $billMonth,
                'billYear' => $billYear,
                'exportType' => $exportTypes,
                'generatedFiles' => $processedPayload->getFullFilePaths(),
            ]);

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);

            return Command::FAILURE;
        }
    }

    private function validateYear(string $billYear): void
    {
        if (!preg_match('/20[0-9][0-9]/', $billYear)) {
            throw new \InvalidArgumentException(sprintf('Bad year format: %s', $billYear));
        }
    }

    private function validateMonth(string $billMonth): void
    {
        if (!preg_match('/0[1-9]10||11|12/', $billMonth)) {
            throw new \InvalidArgumentException(sprintf('Bad month format: %s', $billMonth));
        }
    }

    private function validateExportTypes(array $exportTypes): void
    {
        foreach ($exportTypes as $exportType) {
            if (!\in_array($exportType, self::SupportExportTypes, true)) {
                throw new \InvalidArgumentException(sprintf('Bad export type: %s', $exportType));
            }
        }
    }

    private function validateUsageFileNames(array $usageFileNames): void
    {
        foreach ($usageFileNames as $usageFileName) {
            if (!preg_match(sprintf('/.*\.%s/', self::USAGE_CACHE_EXTENSION), $usageFileName)) {
                throw new \InvalidArgumentException(sprintf('Bad usage cache file extension: %s', $usageFileName));
            }
        }
    }
}

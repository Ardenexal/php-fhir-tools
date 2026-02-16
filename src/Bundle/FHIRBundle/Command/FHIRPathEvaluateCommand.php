<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Command;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Console command to evaluate FHIRPath expressions.
 *
 * @author Ardenexal
 */
#[AsCommand(
    name: 'fhir:path:evaluate',
    description: 'Evaluate a FHIRPath expression against FHIR data',
)]
class FHIRPathEvaluateCommand extends Command
{
    public function __construct(
        private readonly FHIRPathService $pathService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('expression', InputArgument::REQUIRED, 'FHIRPath expression to evaluate')
            ->addArgument('data', InputArgument::OPTIONAL, 'FHIR data (JSON file path or JSON string)')
            ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Output format (json, text, count)', 'text')
            ->addOption('pretty', 'p', InputOption::VALUE_NONE, 'Pretty print JSON output')
            ->setHelp(
                <<<'HELP'
The <info>fhir:path:evaluate</info> command evaluates FHIRPath expressions.

<info>Examples:</info>

  # Evaluate against null context
  <comment>php bin/console fhir:path:evaluate "5 + 3"</comment>

  # Evaluate with JSON data
  <comment>php bin/console fhir:path:evaluate "Patient.name.given" '{"Patient":{"name":[{"given":["John"]}]}}'</comment>

  # Evaluate with JSON file
  <comment>php bin/console fhir:path:evaluate "Patient.name.given" patient.json</comment>

  # Get result count
  <comment>php bin/console fhir:path:evaluate "name.given" patient.json --format=count</comment>

  # Pretty print JSON output
  <comment>php bin/console fhir:path:evaluate "name" patient.json --format=json --pretty</comment>

HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $expression = $input->getArgument('expression');
        $dataArg    = $input->getArgument('data');
        $format     = $input->getOption('format');
        $pretty     = $input->getOption('pretty');

        // Validate expression length and nesting depth
        $maxLength = 10000;
        if (strlen($expression) > $maxLength) {
            $io->error("Expression exceeds maximum length of {$maxLength} characters.");

            return Command::FAILURE;
        }

        $depth    = 0;
        $maxDepth = 0;
        for ($i = 0, $len = strlen($expression); $i < $len; ++$i) {
            if ($expression[$i] === '(' || $expression[$i] === '[') {
                ++$depth;
                $maxDepth = max($maxDepth, $depth);
            } elseif ($expression[$i] === ')' || $expression[$i] === ']') {
                --$depth;
            }
        }
        if ($maxDepth > 50) {
            $io->error('Expression exceeds maximum nesting depth of 50.');

            return Command::FAILURE;
        }

        // Parse data
        $data = null;
        if ($dataArg !== null) {
            if (file_exists($dataArg)) {
                // Prevent path traversal — restrict reads to current working directory
                $realPath = realpath($dataArg);
                $basePath = realpath(getcwd() ?: '.');
                if ($realPath === false || $basePath === false || !str_starts_with($realPath, $basePath . DIRECTORY_SEPARATOR)) {
                    $io->error('File path must be within the current working directory.');

                    return Command::FAILURE;
                }
                $jsonString = file_get_contents($realPath);
                if ($jsonString === false) {
                    $io->error("Failed to read file: {$dataArg}");

                    return Command::FAILURE;
                }
            } else {
                $jsonString = $dataArg;
            }

            $data = json_decode($jsonString, true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                $io->error('Invalid JSON data: ' . json_last_error_msg());

                return Command::FAILURE;
            }
        }

        // Evaluate expression
        try {
            $result = $this->pathService->evaluate($expression, $data);

            // Format output
            match ($format) {
                'json'  => $this->outputJson($io, $result->toArray(), $pretty),
                'count' => $io->writeln((string) $result->count()),
                default => $this->outputText($io, $result->toArray()),
            };

            // Show cache stats if verbose
            if ($output->isVerbose()) {
                $stats = $this->pathService->getCacheStats();
                $io->section('Cache Statistics');
                $io->table(
                    ['Metric', 'Value'],
                    [
                        ['Hits', $stats['hits']],
                        ['Misses', $stats['misses']],
                        ['Size', $stats['size']],
                    ],
                );
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Evaluation failed: ' . $e->getMessage());

            return Command::FAILURE;
        }
    }

    /**
     * @param array<mixed> $result
     */
    private function outputJson(SymfonyStyle $io, array $result, bool $pretty): void
    {
        $flags = $pretty ? JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES : 0;
        $json  = json_encode($result, $flags);
        if ($json !== false) {
            $io->writeln($json);
        }
    }

    /**
     * @param array<mixed> $result
     */
    private function outputText(SymfonyStyle $io, array $result): void
    {
        if (empty($result)) {
            $io->writeln('<comment>Empty result</comment>');

            return;
        }

        foreach ($result as $index => $item) {
            if (is_scalar($item)) {
                $io->writeln("[{$index}] " . (string) $item);
            } elseif (is_array($item) || is_object($item)) {
                $io->writeln("[{$index}] " . json_encode($item, JSON_UNESCAPED_SLASHES));
            } else {
                $io->writeln("[{$index}] " . gettype($item));
            }
        }
    }
}

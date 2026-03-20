<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Command;

use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Console command to validate FHIRPath expression syntax.
 *
 * @author Ardenexal
 */
#[AsCommand(
    name: 'fhir:path:validate',
    description: 'Validate FHIRPath expression syntax',
)]
class FHIRPathValidateCommand extends Command
{
    public function __construct(
        private readonly FHIRPathService $pathService
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('expression', InputArgument::REQUIRED, 'FHIRPath expression to validate')
            ->setHelp(
                <<<'HELP'
The <info>fhir:path:validate</info> command validates FHIRPath expression syntax.

<info>Examples:</info>

  # Validate simple expression
  <comment>php bin/console fhir:path:validate "Patient.name.given"</comment>

  # Validate complex expression
  <comment>php bin/console fhir:path:validate "name.where(use = 'official').given.first()"</comment>

  # Validate expression with operators
  <comment>php bin/console fhir:path:validate "age > 18 and active = true"</comment>

HELP
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $expression = $input->getArgument('expression');

        if ($this->pathService->validate($expression)) {
            $io->success('Expression is valid');

            if ($output->isVerbose()) {
                // Compile to show it can be parsed
                try {
                    $compiled = $this->pathService->compile($expression);
                    $io->writeln('Expression: ' . $compiled->getExpression());
                    $io->writeln('Successfully compiled and ready for evaluation.');
                } catch (\Exception $e) {
                    // Should not happen if validate returned true
                }
            }

            return Command::SUCCESS;
        }

        $io->error('Expression is invalid');

        // Try to get more details by attempting to compile
        try {
            $this->pathService->compile($expression);
        } catch (\Exception $e) {
            $io->writeln('Error details: ' . $e->getMessage());
        }

        return Command::FAILURE;
    }
}

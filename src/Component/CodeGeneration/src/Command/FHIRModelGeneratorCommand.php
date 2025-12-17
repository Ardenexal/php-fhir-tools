<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Command;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRValueSetGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Ask;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use function Symfony\Component\String\s;

/**
 * Symfony Console command for generating FHIR model classes
 *
 * This command orchestrates the entire FHIR code generation process by:
 *
 * - Loading FHIR Implementation Guide packages from registries
 * - Processing StructureDefinitions and ValueSets
 * - Generating PHP classes and enums with proper type hints
 * - Organizing output files by namespace and type
 * - Providing comprehensive error reporting and progress indication
 * - Supporting multiple FHIR versions (R4, R4B, R5)
 *
 * The command integrates with the enhanced error handling system to provide
 * detailed feedback about generation progress and any issues encountered.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
#[AsCommand(name: 'fhir:generate', description: 'Generates FHIR model classes from FHIR definitions.')]
class FHIRModelGeneratorCommand extends Command
{
    /**
     * Default terminology package for FHIR code systems and value sets
     */
    private const string DEFAULT_TERMINOLOGY_PACKAGE = 'hl7.terminology.r4#7.0.0';

    /**
     * Default Implementation Guide packages for each FHIR version
     *
     * @var array<string, array<string>>
     */
    private const array DEFAULT_IG_PACKAGES = [
        'R4'  => [
            'hl7.fhir.r4.core',
        ],
        'R4B' => [
            'hl7.fhir.r4b.core',
        ],
        'R5'  => [
            'hl7.fhir.r5.core',
        ],
    ];

    /**
     * Error collector for aggregating validation and generation errors
     *
     * @var ErrorCollector
     */
    private ErrorCollector $errorCollector;

    /**
     * Filesystem operations handler
     */
    private Filesystem $filesystem;

    /**
     * Context for managing generated types
     */
    private BuilderContext $context;

    /**
     * Package loading and caching handler
     */
    private PackageLoader $packageLoader;

    /**
     * Construct a new FHIR model generator command
     *
     * @param Filesystem     $filesystem    Filesystem operations handler
     * @param BuilderContext $context       Context for managing generated types
     * @param PackageLoader  $packageLoader Package loading and caching handler
     */
    public function __construct(
        Filesystem $filesystem,
        BuilderContext $context,
        PackageLoader $packageLoader,
    ) {
        parent::__construct();
        $this->filesystem     = $filesystem;
        $this->context        = $context;
        $this->packageLoader  = $packageLoader;
        $this->errorCollector = new ErrorCollector();
    }

    /**
     * @param OutputInterface $output
     * @param array<string>   $packages
     *
     * @return int
     */
    public function __invoke(
        OutputInterface $output,
        #[Option(description: 'Implementation Guide packages to include.', name: 'package')]
        #[Ask(question: 'Which FHIR Implementation Guide packages do you want to include?')]
        array $packages = self::DEFAULT_IG_PACKAGES['R4B'],
    ): int {
        try {
            // Clear any previous errors
            $this->errorCollector->clear();

            $loadingPackagesIndicator = new ProgressIndicator($output);
            $loadingPackagesIndicator->start('Loading FHIR Implementation Guide packages...');

            array_unshift($packages, self::DEFAULT_TERMINOLOGY_PACKAGE);

            foreach ($packages as $package) {
                $packageParts = explode('#', $package);
                $version      = $packageParts[1] ?? null;
                $package      = $packageParts[0];

                $loadingPackagesIndicator->setMessage('Loading package ' . $package . ($version ? " version $version" : ''));

                try {
                    $packageMetaData = $this->packageLoader->installPackage(
                        packageName: $package,
                        version: $version,
                        fhirVersion: 'R4B', // Use R4B as specified in the command
                        registry: null,
                        resolveDeps: false, // Don't resolve dependencies for now
                    );

                    // Map FHIR version names to version numbers expected by generateClassesForPackage
                    $fhirVersions    = $packageMetaData->getFhirVersions();
                    $fhirVersionName = $fhirVersions[0] ?? 'R4B';
                    $versionNumber   = match ($fhirVersionName) {
                        'R4'    => '4.0.1',
                        'R4B'   => '4.3.0',
                        'R5'    => '5.0.0',
                        default => '4.3.0', // Default to R4B
                    };

                    $this->generateClassesForPackage($output, $package, $versionNumber);
                } catch (\Throwable $e) {
                    $this->errorCollector->addError(
                        "Failed to process package '{$package}': {$e->getMessage()}",
                        $package,
                        'PACKAGE_PROCESSING_ERROR',
                        'error',
                        [
                            'package_name'    => $package,
                            'version'         => $version,
                            'exception_class' => get_class($e),
                        ],
                    );
                    $output->writeln("<error>Failed to process package {$package}: {$e->getMessage()}</error>");
                }

                $loadingPackagesIndicator->advance();
            }

            $loadingPackagesIndicator->finish('Finished loading FHIR Implementation Guide packages.');

            // Final error report
            if ($this->errorCollector->hasErrors()) {
                $output->writeln('<error>Generation completed with errors:</error>');
                $output->writeln($this->errorCollector->getDetailedOutput());

                return Command::FAILURE;
            }

            if ($this->errorCollector->hasWarnings()) {
                $output->writeln('<comment>Generation completed with warnings:</comment>');
                if ($output->isVerbose()) {
                    $output->writeln($this->errorCollector->getDetailedOutput());
                }
            }

            $output->writeln('<info>FHIR model generation completed successfully!</info>');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $output->writeln("<error>Fatal error during generation: {$e->getMessage()}</error>");
            if ($output->isVerbose()) {
                $output->writeln('<error>Stack trace:</error>');
                $output->writeln($e->getTraceAsString());
            }

            return Command::FAILURE;
        }
    }

    /**
     * @param OutputInterface $output
     * @param string          $version
     * @param PhpNamespace    $namespace
     *
     * @return void
     *
     * @throws \JsonException
     */
    public function buildElementClasses(OutputInterface $output, string $version, PhpNamespace $namespace): void
    {
        $output->writeln('Generating model classes...');

        foreach ($this->context->getDefinitions() as $structureDefinition) {
            if ($structureDefinition['resourceType'] !== 'StructureDefinition') {
                continue;
            }

            // Ignore Profiles that are constraints on other types for now
            if ($structureDefinition['kind'] === 'logical' || (isset($structureDefinition['derivation']) && $structureDefinition['derivation'] === 'constraint')) {
                continue;
            }

            $output->writeln("Generating model class for {$structureDefinition['name']}");
            $generator = new FHIRModelGenerator();

            $class = $generator->generateModelClassWithErrorHandling($structureDefinition, $version, $this->errorCollector);

            if ($class !== null) {
                $this->context->addType($structureDefinition['url'], $class);
                $namespace->add($class);
            } else {
                $output->writeln("<error>Failed to generate class for {$structureDefinition['name']}</error>");
            }
        }

        // Report any collected errors
        if ($this->errorCollector->hasErrors()) {
            $output->writeln('<error>' . $this->errorCollector->getSummary() . '</error>');
            if ($output->isVerbose()) {
                $output->writeln($this->errorCollector->getDetailedOutput());
            }
        }
    }

    /**
     * @param OutputInterface $output
     * @param string          $version
     *
     * @return void
     */
    public function outputFiles(OutputInterface $output, string $version): void
    {
        foreach ($this->context->getTypes() as $type) {
            $elementNamespace = $this->context->getElementNamespace($version);
            $classContents    = self::asPhpFile($type, $elementNamespace);
            $path             = Path::canonicalize(__DIR__ . '/../output/' . $elementNamespace->getName() . '/' . $type->getName() . '.php');
            $this->filesystem->dumpFile($path, $classContents);
            $output->writeln("Generated model class for {$type->getName()}");
        }

        foreach ($this->context->getEnums() as $type) {
            $enumNamespace = $this->context->getEnumNamespace($version);
            $classContents = self::asPhpFile($type, $enumNamespace);
            $path          = Path::canonicalize(__DIR__ . '/../output/' . $enumNamespace->getName() . '/' . $type->getName() . '.php');
            $this->filesystem->dumpFile($path, $classContents);
            $output->writeln("Generated model class for {$type->getName()}");
        }
    }

    /**
     * @param ClassType|EnumType $classType
     * @param PhpNamespace       $namespace
     *
     * @return string
     */
    protected static function asPhpFile(ClassType|EnumType $classType, PhpNamespace $namespace): string
    {
        $printer = new Printer();

        return <<<PHP
        <?php declare(strict_types=1);

        namespace {$namespace->getName()};

        {$printer->printClass($classType, $namespace)}
        PHP;
    }

    /**
     * @param OutputInterface $output
     * @param string          $version
     *
     * @return void
     */
    private function buildEnumsForValuesSets(OutputInterface $output, string $version): void
    {
        $output->writeln('Generating Enums for value sets');

        foreach ($this->context->getPendingEnums() as $key => $pendingEnum) {
            $valueset = $this->context->getDefinition($key);

            if ($valueset === null) {
                $this->errorCollector->addError(
                    "ValueSet definition not found for URL: {$key}",
                    $key,
                    'MISSING_VALUESET_DEFINITION',
                );
                continue;
            }

            $url = $valueset['url'] ?? $key;

            if ($this->context->hasPendingType($url) === false) {
                continue;
            }

            try {
                $output->writeln("Generating enum for {$valueset['name']}");

                $enumGenerator  = new FHIRValueSetGenerator();
                $classGenerator = new FHIRModelGenerator();

                $enumType = $enumGenerator->generateEnum($valueset, $version, $this->context);
                $this->context->getEnumNamespace($version)->add($enumType);
                $this->context->addEnum($url, $enumType);

                $codeType = $classGenerator->generateModelCodeType($enumType, $version, $this->context);
                $this->context->addType($url, $codeType);
                $this->context->removePendingType($url);
                $this->context->removePendingEnum($url);

                $this->context->getElementNamespace($version)->add($codeType);
            } catch (GenerationException $e) {
                $this->errorCollector->addError(
                    $e->getMessage(),
                    $url,
                    'ENUM_GENERATION_ERROR',
                    'error',
                    $e->getContext(),
                );
                $output->writeln("<error>Failed to generate enum for {$valueset['name']}: {$e->getMessage()}</error>");
            } catch (\Throwable $e) {
                $this->errorCollector->addError(
                    "Unexpected error during enum generation: {$e->getMessage()}",
                    $url,
                    'UNEXPECTED_ENUM_ERROR',
                    'error',
                    [
                        'exception_class' => get_class($e),
                        'valueset_name'   => $valueset['name'] ?? 'unknown',
                    ],
                );
                $output->writeln("<error>Unexpected error generating enum for {$valueset['name']}</error>");
            }
        }

        // Report remaining pending types
        $pendingTypes = $this->context->getPendingTypes();
        if (count($pendingTypes) > 0) {
            foreach ($pendingTypes as $pendingTypeUrl) {
                $this->errorCollector->addWarning(
                    "Could not generate type for URL: {$pendingTypeUrl}",
                    $pendingTypeUrl,
                    ['pending_type_url' => $pendingTypeUrl],
                );
                $output->writeln("Warning: Could not generate type for $pendingTypeUrl");
            }

            // Only throw if there are critical errors, not just warnings
            if ($this->errorCollector->getErrorsBySeverity('error')) {
                throw GenerationException::pendingTypesRemaining($pendingTypes);
            }
        }

        // Report final error summary
        if ($this->errorCollector->hasErrors() || $this->errorCollector->hasWarnings()) {
            $output->writeln('<comment>' . $this->errorCollector->getSummary() . '</comment>');
            if ($output->isVerbose()) {
                $output->writeln($this->errorCollector->getDetailedOutput());
            }
        }
    }

    /**
     * @param OutputInterface $output
     * @param string          $package
     * @param string|null     $version
     *
     * @return void
     *
     * @throws \JsonException
     */
    private function generateClassesForPackage(OutputInterface $output, string $package, ?string $version): void
    {
        $namespaceParts = s($package)->split('.');
        $parts          = [];
        foreach ($namespaceParts as $namespace) {
            $parts[] = $namespace->pascal();
        }
        $namespace    = s('\\')->join($parts);
        $namedVersion = match ($version) {
            '4.0.1' => 'R4',
            '4.3.0' => 'R4B',
            '5.0.0' => 'R5',
            default => throw GenerationException::unsupportedFhirVersion($version ?? 'unknown'),
        };
        $targetNamespace  = "Ardenexal\\FHIRTools\\$namespace\\Element";
        $elementNamespace = new PhpNamespace($targetNamespace);
        $this->context->addElementNamespace($namedVersion, $elementNamespace);

        $targetNamespace = "Ardenexal\\FHIRTools\\$namespace\\Enum";
        $enumNamespace   = new PhpNamespace($targetNamespace);
        $this->context->addEnumNamespace($namedVersion, $enumNamespace);


        $this->buildElementClasses($output, $namedVersion, $elementNamespace);


        $this->buildEnumsForValuesSets($output, $namedVersion);
        $this->outputFiles($output, $namedVersion);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Command;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;
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
use Nette\InvalidStateException;

/**
 * Console command that generates PHP model classes from FHIR definition packages.
 *
 * FHIR (Fast Healthcare Interoperability Resources) defines healthcare data structures
 * as JSON "StructureDefinitions". This command downloads those definitions and converts
 * them into strongly-typed PHP classes and enums so they can be used in application code.
 *
 * The generation flow works in three phases per package:
 *
 *  1. **Load** — Download/cache the FHIR package and parse its StructureDefinitions
 *  2. **Build** — Convert each StructureDefinition into a PHP class (via Nette PhpGenerator),
 *     then generate PHP enums for any FHIR ValueSets referenced by those classes
 *  3. **Output** — Write the generated PHP files to `src/Component/Models/src/{version}/`
 *
 * Generated files are organised by FHIR version and type category:
 *
 *   Models/src/
 *   ├── R4/
 *   │   ├── Resource/         ← FHIR resources (Patient, Observation, …)
 *   │   │   └── Patient/      ← Backbone elements nested under a resource
 *   │   ├── DataType/         ← Complex types (HumanName, Address, …)
 *   │   ├── Primitive/        ← Primitive types (String, Boolean, …)
 *   │   └── Enum/             ← ValueSet enums (AdministrativeGender, …)
 *   ├── R4B/
 *   └── R5/
 *
 * Usage:
 *   php bin/console fhir:generate --package=hl7.fhir.r4.core -vvv
 *
 * @see https://www.hl7.org/fhir/structuredefinition.html  StructureDefinition docs
 * @see https://www.hl7.org/fhir/valueset.html             ValueSet docs
 */
#[AsCommand(name: 'fhir:generate', description: 'Generates FHIR model classes from FHIR definitions.')]
class FHIRModelGeneratorCommand extends Command
{
    /**
     * The HL7 terminology package contains CodeSystem and ValueSet definitions shared
     * across all FHIR versions. It's always required, so we prepend it automatically
     * if the user didn't include it in their --package list.
     */
    private const string DEFAULT_TERMINOLOGY_PACKAGE = 'hl7.terminology.r4#7.0.0';

    /**
     * Pre-configured package sets for each FHIR version. Used as the default value
     * for the --package option when no packages are specified by the user.
     *
     * @var array<string, array<string>>
     */
    private const array DEFAULT_IG_PACKAGES = [
        'R4' => [
            'hl7.terminology.r4#7.0.0',
            'hl7.fhir.r4.core#4.0.1',
        ],
        'R4B' => [
            'hl7.terminology.r4b#7.0.0',
            'hl7.fhir.r4b.core#4.3.0',
        ],
        'R5' => [
            'hl7.terminology.r5#7.0.0',
            'hl7.fhir.r5.core#5.0.0',
        ],
    ];

    /** Collects non-fatal errors and warnings during generation for reporting at the end. */
    private ErrorCollector $errorCollector;

    private Filesystem $filesystem;

    /**
     * One BuilderContext per FHIR version. Each context holds the loaded StructureDefinitions,
     * generated classes, pending enums, and namespace registrations for that version.
     *
     * @var array{
     *  R4: BuilderContext,
     *  R4B: BuilderContext,
     *  R5:  BuilderContext
     * }
     */
    private array $context;

    private PackageLoader $packageLoader;

    public function __construct(
        Filesystem $filesystem,
        PackageLoader $packageLoader,
    ) {
        parent::__construct();
        $this->filesystem = $filesystem;
        $this->context    = [
            'R4'  => new BuilderContext(),
            'R4B' => new BuilderContext(),
            'R5'  => new BuilderContext(),
        ];
        $this->packageLoader  = $packageLoader;
        $this->errorCollector = new ErrorCollector();
    }

    /**
     * Entry point invoked by Symfony Console when the user runs `fhir:generate`.
     *
     * Symfony's invokable command pattern uses __invoke() instead of execute().
     * The #[Option] and #[Ask] attributes tell Symfony how to wire CLI arguments
     * to these parameters automatically.
     *
     * @param OutputInterface $output      Console output for writing messages
     * @param array<string>   $packages    FHIR packages to process, e.g. ['hl7.fhir.r4.core#4.0.1']
     * @param bool            $offlineMode When true, only use locally cached packages (no network)
     *
     * @return int Command::SUCCESS (0) or Command::FAILURE (1)
     */
    public function __invoke(
        OutputInterface $output,
        #[Option(description: 'Implementation Guide packages to include.', name: 'package')]
        #[Ask(question: 'Which FHIR Implementation Guide packages do you want to include?')]
        array $packages = self::DEFAULT_IG_PACKAGES['R4B'],
        #[Option(description: 'Work offline using only cached packages', name: 'offline')]
        bool $offlineMode = false,
    ): int {
        try {
            $this->errorCollector->clear();

            if ($offlineMode) {
                $output->writeln('<info>Running in offline mode - using cached packages only</info>');
            }

            return $this->executeGeneration($output, $packages, $offlineMode);
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
     * Main orchestration method — clears output, processes each package, then reports results.
     *
     * @param array<string> $packages
     */
    private function executeGeneration(OutputInterface $output, array $packages, bool $offlineMode = false): int
    {
        $output->writeln('<info>Generating FHIR models...</info>');

        $this->clearOutputDirectory($output);

        $loadingPackagesIndicator = new ProgressIndicator($output);
        $loadingPackagesIndicator->start('Loading FHIR Implementation Guide packages...');

        // The terminology package defines shared CodeSystems and ValueSets (e.g. AdministrativeGender).
        // Ensure it's always loaded first so those definitions are available when building classes.
        if (! in_array(self::DEFAULT_TERMINOLOGY_PACKAGE, $packages, true)) {
            array_unshift($packages, self::DEFAULT_TERMINOLOGY_PACKAGE);
        }

        foreach ($packages as $package) {
            // Packages are specified as "name#version", e.g. "hl7.fhir.r4.core#4.0.1"
            $packageParts = explode('#', $package);
            $version      = $packageParts[1] ?? null;
            $package      = $packageParts[0];

            $loadingPackagesIndicator->setMessage('Loading package ' . $package . ($version ? " version $version" : ''));

            try {
                $this->processPackage($output, $package, $version, $offlineMode);
            } catch (\Throwable $e) {
                // Record the error but keep processing remaining packages
                $this->errorCollector->addError(
                    "Failed to process package '{$package}': {$e->getMessage()}",
                    $package,
                    'PACKAGE_PROCESSING_ERROR',
                    'error',
                    [
                        'package_name'    => $package,
                        'version'         => $version,
                        'exception_class' => get_class($e),
                        'line'            => $e->getLine(),
                        'file'            => $e->getFile(),
                    ],
                );
                $output->writeln("<error>Failed to process package {$package}: {$e->getMessage()}</error>");
            }

            $loadingPackagesIndicator->advance();
        }

        $loadingPackagesIndicator->finish('Finished loading FHIR Implementation Guide packages.');

        // Report final status — any collected errors mean the generation is incomplete
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
    }

    /**
     * Download/cache a single FHIR package, then generate classes for each FHIR version it supports.
     *
     * A single package can support multiple FHIR versions (though most support exactly one).
     * For each version, we load the StructureDefinitions into the version-specific BuilderContext
     * and run the full generate → build enums → output pipeline.
     */
    private function processPackage(OutputInterface $output, string $package, ?string $version, bool $offlineMode): void
    {
        $packageMetaData = $this->packageLoader->installPackage(
            packageName: $package,
            version: $version,
            registry: null,
            resolveDeps: false,
            offlineMode: $offlineMode,
        );

        foreach ($packageMetaData->getFhirVersions() as $fhirVersion) {
            $definitions = $this->packageLoader->loadPackageStructureDefinitions($packageMetaData);

            // Validate the FHIR version is one we support
            match ($fhirVersion) {
                'R4', 'R4B', 'R5' => null,
                default           => throw PackageException::unsupportedFhirVersion($fhirVersion, ['R4', 'R4B', 'R5']),
            };

            $this->context[$fhirVersion]->loadDefinitions($definitions);
            $this->generateClassesForPackage($output, $package, $fhirVersion);
        }
    }

    /**
     * Remove previously generated version directories (R4/, R4B/, R5/) before regenerating.
     *
     * Only removes directories matching the FHIR version naming pattern (e.g. "R4", "R4B", "R5")
     * to avoid accidentally deleting other files in the Models/src directory.
     */
    private function clearOutputDirectory(OutputInterface $output): void
    {
        $basePath = Path::canonicalize(__DIR__ . '/../../../Models/src');

        if ($this->filesystem->exists($basePath)) {
            $output->writeln('<comment>Clearing existing output directory...</comment>');

            $versionDirs = glob($basePath . '/*', GLOB_ONLYDIR);

            if ($versionDirs !== false) {
                foreach ($versionDirs as $versionDir) {
                    $versionName = basename($versionDir);
                    if (preg_match('/^R\d+[A-Z]*$/', $versionName)) {
                        $output->writeln("<comment>Clearing {$versionName} directory...</comment>");
                        $this->filesystem->remove($versionDir);
                    }
                }
            }

            $output->writeln('<info>Output directories cleared successfully.</info>');
        }

        $this->filesystem->mkdir($basePath, 0755);
    }

    /**
     * Run the full generation pipeline for a single FHIR version: build classes, build enums, write files.
     *
     * This sets up four Nette PhpNamespace objects — one per output category (Resource, DataType,
     * Primitive, Enum) — which act as containers for the generated class/enum types. The namespaces
     * are registered in BuilderContext so that cross-references between types can be resolved.
     *
     * @throws \JsonException
     */
    private function generateClassesForPackage(OutputInterface $output, string $package, string $fhirVersion): void
    {
        // All generated classes live under this base namespace, e.g.
        // "Ardenexal\FHIRTools\Component\Models\R4"
        $baseNamespace = "Ardenexal\\FHIRTools\\Component\\Models\\{$fhirVersion}";

        $resourceNamespace  = new PhpNamespace("{$baseNamespace}\\Resource");
        $dataTypeNamespace  = new PhpNamespace("{$baseNamespace}\\DataType");
        $primitiveNamespace = new PhpNamespace("{$baseNamespace}\\Primitive");
        $enumNamespace      = new PhpNamespace("{$baseNamespace}\\Enum");

        // Register namespaces in context so FHIRModelGenerator can resolve cross-type references
        // (e.g. a Patient resource referencing the HumanName data type)
        $this->context[$fhirVersion]->addElementNamespace($fhirVersion, $resourceNamespace);
        $this->context[$fhirVersion]->addEnumNamespace($fhirVersion, $enumNamespace);
        $this->context[$fhirVersion]->addPrimitiveNamespace($fhirVersion, $primitiveNamespace);
        $this->context[$fhirVersion]->addDatatypeNamespace($fhirVersion, $dataTypeNamespace);

        // Phase 1: Generate PHP classes from StructureDefinitions
        $this->buildClasses($output, $fhirVersion, $resourceNamespace, $dataTypeNamespace, $primitiveNamespace);

        // Phase 2: Generate PHP enums from ValueSets that were referenced during class generation.
        // Enum failures are non-fatal — we log them and continue so we still get the class files.
        try {
            $this->buildEnums($output, $fhirVersion);
        } catch (\Throwable $e) {
            $this->errorCollector->addError(
                "Enum generation failed but continuing with class generation: {$e->getMessage()}",
                $package,
                'ENUM_GENERATION_PARTIAL_FAILURE',
                'warning',
                [
                    'exception_class' => get_class($e),
                    'package_name'    => $package,
                ],
            );
            $output->writeln('<comment>Warning: Enum generation failed but continuing with class generation</comment>');
        }

        // Phase 3: Write all generated classes and enums to disk
        $this->outputGeneratedFiles($output, $fhirVersion);
    }

    /**
     * Iterate over loaded StructureDefinitions and generate a PHP class for each one.
     *
     * Each StructureDefinition has a "kind" that determines which namespace category it belongs to:
     *   - "resource"       → Resource/    (Patient, Observation, Encounter, …)
     *   - "complex-type"   → DataType/    (HumanName, Address, CodeableConcept, …)
     *   - "primitive-type"  → Primitive/   (String, Boolean, DateTime, …)
     *
     * We skip two kinds of definitions:
     *   - "logical" models (abstract/non-instantiable)
     *   - "constraint" derivations (profiles that constrain an existing type, e.g. US Core Patient)
     *
     * @throws \JsonException
     */
    private function buildClasses(OutputInterface $output, string $version, PhpNamespace $resourceNamespace, PhpNamespace $dataTypeNamespace, PhpNamespace $primitiveNamespace): void
    {
        $output->writeln('Generating model classes...');

        $resourceCount  = 0;
        $dataTypeCount  = 0;
        $primitiveCount = 0;

        foreach ($this->context[$version]->getDefinitions() as $structureDefinition) {
            if ($structureDefinition['resourceType'] !== 'StructureDefinition') {
                continue;
            }

            // Skip profiles (constraints on existing types) and logical models
            if ($structureDefinition['kind'] === 'logical' || (isset($structureDefinition['derivation']) && $structureDefinition['derivation'] === 'constraint')) {
                continue;
            }

            $kind = $structureDefinition['kind'] ?? 'unknown';
            $name = $structureDefinition['name'] ?? 'Unknown';

            // Route the generated class to the correct namespace based on its kind
            $targetNamespace = match ($kind) {
                'resource'       => $resourceNamespace,
                'complex-type'   => $dataTypeNamespace,
                'primitive-type' => $primitiveNamespace,
                default          => null
            };

            if ($targetNamespace === null) {
                $output->writeln("<comment>Skipping {$name} with unsupported kind: {$kind}</comment>");

                continue;
            }

            $output->writeln("Generating class for {$name} (kind: {$kind})");
            $generator = new FHIRModelGenerator();

            $class = $generator->generateModelClassWithErrorHandling($structureDefinition, $version, $this->errorCollector, $this->context[$version]);

            if ($class !== null) {
                // Register the class in context (for cross-reference resolution) and in its namespace
                $this->context[$version]->addType($structureDefinition['url'], $targetNamespace->getName(), $class);
                $targetNamespace->add($class);

                match ($kind) {
                    'resource'       => $resourceCount++,
                    'complex-type'   => $dataTypeCount++,
                    'primitive-type' => $primitiveCount++,
                    default          => null
                };
            } else {
                $output->writeln("<error>Failed to generate class for {$name}</error>");
            }
        }

        $output->writeln("<info>Generated {$resourceCount} resources, {$dataTypeCount} data types, {$primitiveCount} primitives</info>");

        if ($this->errorCollector->hasErrors()) {
            $output->writeln('<error>' . $this->errorCollector->getSummary() . '</error>');
            if ($output->isVerbose()) {
                $output->writeln($this->errorCollector->getDetailedOutput());
            }
        }
    }

    /**
     * Generate PHP enums from FHIR ValueSets that were referenced during class building.
     *
     * When FHIRModelGenerator encounters a property bound to a ValueSet (e.g. Patient.gender
     * is bound to the AdministrativeGender ValueSet), it records that ValueSet URL as a
     * "pending enum" in BuilderContext. This method processes all those pending enums.
     *
     * For each ValueSet, two things are generated:
     *   1. A PHP enum (e.g. `AdministrativeGender`) with cases for each coded value
     *   2. A "code type" wrapper class (e.g. `AdministrativeGenderType`) that extends FHIRCode
     *      and associates the enum with FHIR's type system
     *
     * Duplicate detection is needed because multiple StructureDefinitions can reference the
     * same ValueSet — we only want to generate each enum once.
     */
    private function buildEnums(OutputInterface $output, string $version): void
    {
        $output->writeln('Generating Enums for value sets');

        foreach ($this->context[$version]->getPendingEnums() as $key => $pendingEnum) {
            $valueset = $this->context[$version]->getDefinition($key);

            if ($valueset === null) {
                $this->errorCollector->addError(
                    "ValueSet definition not found for URL: {$key}",
                    $key,
                    'MISSING_VALUESET_DEFINITION',
                );

                continue;
            }

            $url = $valueset['url'] ?? $key;

            if ($this->context[$version]->hasPendingType($url) === false) {
                continue;
            }

            // Skip if we already generated this enum (can happen when multiple
            // StructureDefinitions reference the same ValueSet)
            if ($this->context[$version]->getEnum($url) !== null) {
                $output->writeln("Enum for {$valueset['name']} already exists, skipping generation");
                $this->context[$version]->removePendingType($url);
                $this->context[$version]->removePendingEnum($url);

                continue;
            }

            try {
                $output->writeln("Generating enum for {$valueset['name']}");

                $enumGenerator  = new FHIRValueSetGenerator();
                $classGenerator = new FHIRModelGenerator();

                // Step 1: Generate the PHP enum from the ValueSet definition
                $enumType = $enumGenerator->generateEnum($valueset, $version, $this->context[$version]);

                // Step 2: Register the enum in its namespace and context.
                // Nette's PhpNamespace throws InvalidStateException if a type with the same
                // name already exists, so we handle that gracefully.
                $enumNamespace = $this->context[$version]->getEnumNamespace($version);
                $enumTypeName  = $enumType->getName();
                if ($enumTypeName !== null) {
                    try {
                        $enumNamespace->add($enumType);
                        $this->context[$version]->addEnum($url, $enumNamespace->getName(), $enumType);
                    } catch (InvalidStateException $e) {
                        if (str_contains($e->getMessage(), 'already exists')) {
                            $output->writeln("Enum class {$enumTypeName} already exists in namespace, skipping namespace addition");
                            $this->context[$version]->addEnum($url, $enumNamespace->getName(), $enumType);
                        } else {
                            throw $e;
                        }
                    }
                } else {
                    $this->context[$version]->addEnum($url, $enumNamespace->getName(), $enumType);
                }

                // Step 3: Generate a "code type" wrapper class that bridges the enum to FHIR's
                // type system. For example, AdministrativeGenderType extends FHIRCode and
                // references the AdministrativeGender enum. This goes in DataType/ because
                // code types are data types in FHIR's type hierarchy.
                $codeType          = $classGenerator->generateModelCodeType($enumType, $version, $this->context[$version]);
                $dataTypeNamespace = $this->context[$version]->getDatatypeNamespace($version);
                $this->context[$version]->addType($url, $dataTypeNamespace->getName(), $codeType);
                $this->context[$version]->removePendingType($url);
                $this->context[$version]->removePendingEnum($url);

                $codeTypeName = $codeType->getName();
                if ($codeTypeName !== null) {
                    try {
                        $dataTypeNamespace->add($codeType);
                    } catch (InvalidStateException $e) {
                        if (str_contains($e->getMessage(), 'already exists')) {
                            $output->writeln("Code type class {$codeTypeName} already exists in namespace, skipping namespace addition");
                        } else {
                            throw $e;
                        }
                    }
                }
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

        // Any types still pending after enum generation means we couldn't resolve them
        $pendingTypes = $this->context[$version]->getPendingTypes();
        if (count($pendingTypes) > 0) {
            foreach ($pendingTypes as $pendingTypeUrl) {
                $this->errorCollector->addWarning(
                    "Could not generate type for URL: {$pendingTypeUrl}",
                    $pendingTypeUrl,
                    ['pending_type_url' => $pendingTypeUrl],
                );
                $output->writeln("Warning: Could not generate type for $pendingTypeUrl");
            }

            if ($this->errorCollector->getErrorsBySeverity('error')) {
                throw GenerationException::pendingTypesRemaining($pendingTypes);
            }
        }

        if ($this->errorCollector->hasErrors() || $this->errorCollector->hasWarnings()) {
            $output->writeln('<comment>' . $this->errorCollector->getSummary() . '</comment>');
            if ($output->isVerbose()) {
                $output->writeln($this->errorCollector->getDetailedOutput());
            }
        }
    }

    /**
     * Write all generated classes and enums to disk as PHP files.
     *
     * Each generated type is written to a path determined by its FHIR version and category:
     *   Models/src/{version}/{category}/{ClassName}.php
     *
     * For example: Models/src/R4/Resource/FHIRPatient.php
     */
    private function outputGeneratedFiles(OutputInterface $output, string $version): void
    {
        $baseNamespace = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}";

        foreach ($this->context[$version]->getTypes() as $type) {
            $namespace     = $this->determineNamespace($type->asClassType(), $baseNamespace);
            $classContents = self::asPhpFile($type->asClassType(), $type->namespace);
            $outputPath    = $this->getOutputPath($version, $type->class, $namespace);

            $directory = dirname($outputPath);
            if (! $this->filesystem->exists($directory)) {
                $this->filesystem->mkdir($directory, 0755);
            }

            $this->filesystem->dumpFile($outputPath, $classContents);
            $output->writeln("Generated class for {$type->getClassName()}");
        }

        foreach ($this->context[$version]->getEnums() as $type) {
            $enumNamespace = new PhpNamespace("{$baseNamespace}\\Enum");
            $classContents = self::asPhpFile($type->class, $type->namespace);
            $outputPath    = $this->getOutputPath($version, $type->class, $enumNamespace);

            $directory = dirname($outputPath);
            if (! $this->filesystem->exists($directory)) {
                $this->filesystem->mkdir($directory, 0755);
            }

            $this->filesystem->dumpFile($outputPath, $classContents);
            $output->writeln("Generated enum for {$type->getClassName()}");
        }
    }

    /**
     * Determine the correct output namespace for a generated class based on its PHP attributes.
     *
     * Every generated class is tagged with exactly one of these attributes by FHIRModelGenerator:
     *   - FhirResource      → Resource namespace
     *   - FHIRBackboneElement → Resource namespace (if nested, e.g. "Patient.contact")
     *                           or DataType namespace (if top-level, e.g. "Dosage")
     *   - FHIRPrimitive      → Primitive namespace
     *   - FHIRComplexType    → DataType namespace
     *
     * The backbone element distinction matters because elements like "Patient.contact" are
     * nested inside a resource (so they go in Resource/), while standalone backbone elements
     * like "Dosage" are reusable data types (so they go in DataType/). We check the
     * "elementPath" attribute argument — a dot means it's nested (e.g. "Communication.payload").
     */
    private function determineNamespace(ClassType $type, string $baseNamespace): PhpNamespace
    {
        foreach ($type->getAttributes() as $attribute) {
            $attributeName = $attribute->getName();

            if (str_contains($attributeName, 'FhirResource')) {
                return new PhpNamespace("{$baseNamespace}\\Resource");
            }

            if (str_contains($attributeName, 'FHIRBackboneElement')) {
                $args        = $attribute->getArguments();
                $elementPath = $args['elementPath'] ?? '';
                if (str_contains($elementPath, '.')) {
                    return new PhpNamespace("{$baseNamespace}\\Resource");
                }

                return new PhpNamespace("{$baseNamespace}\\DataType");
            }

            if (str_contains($attributeName, 'FHIRPrimitive')) {
                return new PhpNamespace("{$baseNamespace}\\Primitive");
            }

            if (str_contains($attributeName, 'FHIRComplexType')) {
                return new PhpNamespace("{$baseNamespace}\\DataType");
            }
        }

        // Default to DataType for any class without a recognized attribute
        return new PhpNamespace("{$baseNamespace}\\DataType");
    }

    /**
     * Resolve the filesystem path where a generated class or enum should be written.
     *
     * Most types go to: Models/src/{version}/{category}/{TypeName}.php
     *
     * Backbone elements that belong to a resource get a subdirectory:
     *   Models/src/{version}/Resource/{ResourceName}/{BackboneElementName}.php
     *
     * For example, FHIRPatientContact (a backbone element of Patient) goes to:
     *   Models/src/R4/Resource/Patient/FHIRPatientContact.php
     *
     * This keeps resource directories organised when a resource has many backbone elements.
     */
    private function getOutputPath(string $version, ClassType|EnumType $type, PhpNamespace $namespace): string
    {
        $basePath = Path::canonicalize(__DIR__ . '/../../../Models/src');

        // Extract the last segment of the namespace to determine the category
        // e.g. "Ardenexal\...\R4\Resource" → "Resource"
        $namespaceParts = explode('\\', $namespace->getName());
        $typeCategory   = end($namespaceParts);

        // If this is a backbone element in the Resource category, nest it under its parent resource
        $typeName = $type->getName();
        if ($typeName !== null && $type instanceof ClassType && $typeCategory === 'Resource') {
            $parentResource = $this->getBackboneParentResource($type);
            if ($parentResource !== null) {
                return Path::canonicalize("{$basePath}/{$version}/Resource/{$parentResource}/{$typeName}.php");
            }
        }

        return Path::canonicalize("{$basePath}/{$version}/{$typeCategory}/{$typeName}.php");
    }

    /**
     * If this class is a backbone element, return the name of the resource it belongs to.
     *
     * Reads the "parentResource" argument from the FHIRBackboneElement attribute, which is
     * always set by FHIRModelGenerator (extracted from the StructureDefinition's element path).
     *
     * @return string|null The parent resource name (e.g. "Patient"), or null if not a backbone element
     */
    private function getBackboneParentResource(ClassType $type): ?string
    {
        foreach ($type->getAttributes() as $attribute) {
            if (str_contains($attribute->getName(), 'FHIRBackboneElement')) {
                return $attribute->getArguments()['parentResource'] ?? null;
            }
        }

        return null;
    }

    /**
     * Wrap a generated class or enum in a complete PHP file with strict types and namespace declaration.
     *
     * Uses Nette's Printer to render the class/enum body, then prepends the standard PHP
     * file header (declare, namespace).
     */
    protected static function asPhpFile(ClassType|EnumType $classType, string $namespace): string
    {
        $printer = new Printer();

        return <<<PHP
        <?php declare(strict_types=1);

        namespace {$namespace};

        {$printer->printClass($classType, new PhpNamespace($namespace))}
        PHP;
    }
}

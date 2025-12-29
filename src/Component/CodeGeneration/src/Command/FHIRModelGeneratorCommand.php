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
use Nette\InvalidStateException;

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
        'R4' => [
            'hl7.fhir.r4.core',
        ],
        'R4B' => [
            'hl7.fhir.r4b.core',
        ],
        'R5' => [
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
     * @param bool            $modelsComponent
     *
     * @return int
     */
    public function __invoke(
        OutputInterface $output,
        #[Option(description: 'Implementation Guide packages to include.', name: 'package')]
        #[Ask(question: 'Which FHIR Implementation Guide packages do you want to include?')]
        array $packages = self::DEFAULT_IG_PACKAGES['R4B'],
        #[Option(description: 'Generate models for the Models component', name: 'models-component')]
        bool $modelsComponent = false,
    ): int {
        try {
            // Clear any previous errors
            $this->errorCollector->clear();

            // Check if Models component generation is requested
            if ($modelsComponent) {
                return $this->generateForModelsComponent($output, $packages);
            }

            return $this->executeGeneration(
                output: $output,
                packages: $packages,
                isModelsComponent: false,
            );
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
     * Generate FHIR models for the Models component
     *
     * @param OutputInterface $output
     * @param array<string>   $packages
     *
     * @return int
     */
    private function generateForModelsComponent(OutputInterface $output, array $packages): int
    {
        return $this->executeGeneration(
            output: $output,
            packages: $packages,
            isModelsComponent: true,
        );
    }

    /**
     * Execute FHIR model generation with unified logic
     *
     * @param OutputInterface $output
     * @param array<string>   $packages
     * @param bool            $isModelsComponent
     *
     * @return int
     */
    private function executeGeneration(OutputInterface $output, array $packages, bool $isModelsComponent): int
    {
        $componentType = $isModelsComponent ? 'Models component' : 'FHIR model';
        $output->writeln("<info>Generating {$componentType}s...</info>");

        // Clear appropriate output directory before generation
        if ($isModelsComponent) {
            $this->clearModelsComponentOutputDirectory($output);
        } else {
            $this->clearOutputDirectory($output);
        }

        $loadingPackagesIndicator = new ProgressIndicator($output);
        $progressMessage          = $isModelsComponent
            ? 'Loading FHIR Implementation Guide packages for Models component...'
            : 'Loading FHIR Implementation Guide packages...';
        $loadingPackagesIndicator->start($progressMessage);

        // Add terminology package if not already present
        if (!in_array(self::DEFAULT_TERMINOLOGY_PACKAGE, $packages, true)) {
            array_unshift($packages, self::DEFAULT_TERMINOLOGY_PACKAGE);
        }

        foreach ($packages as $package) {
            $packageParts = explode('#', $package);
            $version      = $packageParts[1] ?? null;
            $package      = $packageParts[0];

            $loadingPackagesIndicator->setMessage('Loading package ' . $package . ($version ? " version $version" : ''));

            try {
                if ($isModelsComponent) {
                    $this->processPackageForModelsComponent($output, $package, $version);
                } else {
                    $this->processPackageForRegularGeneration($output, $package, $version);
                }
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

        $finishMessage = $isModelsComponent
            ? 'Finished loading FHIR Implementation Guide packages for Models component.'
            : 'Finished loading FHIR Implementation Guide packages.';
        $loadingPackagesIndicator->finish($finishMessage);

        // Final error report
        if ($this->errorCollector->hasErrors()) {
            $errorMessage = $isModelsComponent
                ? '<error>Models component generation completed with errors:</error>'
                : '<error>Generation completed with errors:</error>';
            $output->writeln($errorMessage);
            $output->writeln($this->errorCollector->getDetailedOutput());

            return Command::FAILURE;
        }

        if ($this->errorCollector->hasWarnings()) {
            $warningMessage = $isModelsComponent
                ? '<comment>Models component generation completed with warnings:</comment>'
                : '<comment>Generation completed with warnings:</comment>';
            $output->writeln($warningMessage);
            if ($output->isVerbose()) {
                $output->writeln($this->errorCollector->getDetailedOutput());
            }
        }

        $successMessage = $isModelsComponent
            ? '<info>FHIR Models component generation completed successfully!</info>'
            : '<info>FHIR model generation completed successfully!</info>';
        $output->writeln($successMessage);

        return Command::SUCCESS;
    }

    /**
     * Process a package for Models component generation
     *
     * @param OutputInterface $output
     * @param string          $package
     * @param string|null     $version
     *
     * @return void
     */
    private function processPackageForModelsComponent(OutputInterface $output, string $package, ?string $version): void
    {
        // Detect FHIR version from package name
        $detectedFhirVersion = 'R4B'; // default
        if (str_contains($package, 'r4.core')) {
            $detectedFhirVersion = 'R4';
        } elseif (str_contains($package, 'r4b.core')) {
            $detectedFhirVersion = 'R4B';
        } elseif (str_contains($package, 'r5.core')) {
            $detectedFhirVersion = 'R5';
        }

        $packageMetaData = $this->packageLoader->installPackage(
            packageName: $package,
            version: $version,
            fhirVersion: $detectedFhirVersion,
            registry: null,
            resolveDeps: false,
        );

        // Load package definitions into context
        $this->packageLoader->loadPackageToContext($packageMetaData, $detectedFhirVersion);

        // Map FHIR version names to version numbers
        $fhirVersions    = $packageMetaData->getFhirVersions();
        $fhirVersionName = $fhirVersions[0] ?? $detectedFhirVersion;
        $versionNumber   = match ($fhirVersionName) {
            'R4'    => '4.0.1',
            'R4B'   => '4.3.0',
            'R5'    => '5.0.0',
            default => '4.3.0',
        };

        $this->generateModelsComponentClassesForPackage($output, $package, $versionNumber, $fhirVersionName);
    }

    /**
     * Process a package for regular generation
     *
     * @param OutputInterface $output
     * @param string          $package
     * @param string|null     $version
     *
     * @return void
     */
    private function processPackageForRegularGeneration(OutputInterface $output, string $package, ?string $version): void
    {
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
    }

    /**
     * Clear the regular output directory before generation
     *
     * @param OutputInterface $output
     *
     * @return void
     */
    private function clearOutputDirectory(OutputInterface $output): void
    {
        $outputPath = Path::canonicalize(__DIR__ . '/../output');

        if ($this->filesystem->exists($outputPath)) {
            $output->writeln('<comment>Clearing existing output directory...</comment>');
            $this->filesystem->remove($outputPath);
            $output->writeln('<info>Output directory cleared successfully.</info>');
        }

        // Ensure the output directory exists
        $this->filesystem->mkdir($outputPath, 0755);
    }

    /**
     * Clear the Models component output directory before generation
     *
     * @param OutputInterface $output
     *
     * @return void
     */
    private function clearModelsComponentOutputDirectory(OutputInterface $output): void
    {
        $basePath = Path::canonicalize(__DIR__ . '/../../../Models/src');

        if ($this->filesystem->exists($basePath)) {
            $output->writeln('<comment>Clearing existing Models component output directory...</comment>');

            // Get all version directories (R4, R4B, R5, etc.)
            $versionDirs = glob($basePath . '/*', GLOB_ONLYDIR);

            if ($versionDirs !== false) {
                foreach ($versionDirs as $versionDir) {
                    $versionName = basename($versionDir);
                    // Only clear directories that look like FHIR version names
                    if (preg_match('/^R\d+[A-Z]*$/', $versionName)) {
                        $output->writeln("<comment>Clearing {$versionName} directory...</comment>");
                        $this->filesystem->remove($versionDir);
                    }
                }
            }

            $output->writeln('<info>Models component output directories cleared successfully.</info>');
        }

        // Ensure the base directory exists
        $this->filesystem->mkdir($basePath, 0755);
    }

    /**
     * Generate classes for a package using Models component structure
     *
     * @param OutputInterface $output
     * @param string          $package
     * @param string          $version
     * @param string          $fhirVersionName
     *
     * @return void
     *
     * @throws \JsonException
     */
    private function generateModelsComponentClassesForPackage(OutputInterface $output, string $package, string $version, string $fhirVersionName): void
    {
        // Use Models component namespace structure
        $baseNamespace = "Ardenexal\\FHIRTools\\Component\\Models\\{$fhirVersionName}";

        // Create namespaces for different types
        $resourceNamespace  = new PhpNamespace("{$baseNamespace}\\Resource");
        $dataTypeNamespace  = new PhpNamespace("{$baseNamespace}\\DataType");
        $primitiveNamespace = new PhpNamespace("{$baseNamespace}\\Primitive");
        $enumNamespace      = new PhpNamespace("{$baseNamespace}\\Enum");

        // Set up all namespaces in the context
        $this->context->addElementNamespace($fhirVersionName, $resourceNamespace);
        $this->context->addEnumNamespace($fhirVersionName, $enumNamespace);
        $this->context->addPrimitiveNamespace($fhirVersionName, $primitiveNamespace);
        $this->context->addDatatypeNamespace($fhirVersionName, $dataTypeNamespace);

        // Build different types of classes
        $this->buildModelsComponentClasses($output, $fhirVersionName, $resourceNamespace, $dataTypeNamespace, $primitiveNamespace);

        // Try to build enums but don't fail if there are errors
        try {
            $this->buildEnumsForValuesSets($output, $fhirVersionName);
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

        $this->outputModelsComponentFiles($output, $fhirVersionName);
    }

    /**
     * Output files using Models component directory structure
     *
     * @param OutputInterface $output
     * @param string          $version
     *
     * @return void
     */
    private function outputModelsComponentFiles(OutputInterface $output, string $version): void
    {
        $baseNamespace = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}";

        foreach ($this->context->getTypes() as $type) {
            // Determine the correct namespace based on the class attributes
            $namespace     = $this->determineModelsComponentNamespace($type, $baseNamespace);
            $classContents = self::asPhpFile($type, $namespace);

            // Determine output path based on type and backbone element status
            $outputPath = $this->getModelsComponentOutputPath($version, $type, $namespace);

            // Ensure directory exists for backbone elements
            $directory = dirname($outputPath);
            if (!$this->filesystem->exists($directory)) {
                $this->filesystem->mkdir($directory, 0755);
            }

            $this->filesystem->dumpFile($outputPath, $classContents);
            $output->writeln("Generated Models component class for {$type->getName()}");
        }

        foreach ($this->context->getEnums() as $type) {
            $enumNamespace = new PhpNamespace("{$baseNamespace}\\Enum");
            $classContents = self::asPhpFile($type, $enumNamespace);

            $outputPath = $this->getModelsComponentOutputPath($version, $type, $enumNamespace);

            // Ensure directory exists
            $directory = dirname($outputPath);
            if (!$this->filesystem->exists($directory)) {
                $this->filesystem->mkdir($directory, 0755);
            }

            $this->filesystem->dumpFile($outputPath, $classContents);
            $output->writeln("Generated Models component enum for {$type->getName()}");
        }
    }

    /**
     * Determine the correct namespace for a type based on its attributes
     *
     * @param ClassType $type
     * @param string    $baseNamespace
     *
     * @return PhpNamespace
     */
    private function determineModelsComponentNamespace(ClassType $type, string $baseNamespace): PhpNamespace
    {
        // Check class attributes to determine the type category
        foreach ($type->getAttributes() as $attribute) {
            $attributeName = $attribute->getName();

            if (str_contains($attributeName, 'FhirResource') || str_contains($attributeName, 'FHIRBackboneElement')) {
                return new PhpNamespace("{$baseNamespace}\\Resource");
            }

            if (str_contains($attributeName, 'FHIRPrimitive')) {
                return new PhpNamespace("{$baseNamespace}\\Primitive");
            }

            if (str_contains($attributeName, 'FHIRComplexType')) {
                return new PhpNamespace("{$baseNamespace}\\DataType");
            }
        }

        // Fallback: try to determine from class name patterns
        $className = $type->getName();
        if ($className !== null) {
            // Check if it's a known FHIR resource
            if ($this->isKnownFHIRResource($className)) {
                return new PhpNamespace("{$baseNamespace}\\Resource");
            }

            // Check if it's a primitive type (usually shorter names)
            if ($this->isKnownFHIRPrimitive($className)) {
                return new PhpNamespace("{$baseNamespace}\\Primitive");
            }
        }

        // Default to DataType for complex types
        return new PhpNamespace("{$baseNamespace}\\DataType");
    }

    /**
     * Check if a class name represents a known FHIR resource
     *
     * @param string $className
     *
     * @return bool
     */
    private function isKnownFHIRResource(string $className): bool
    {
        // Remove FHIR prefix
        $name = str_starts_with($className, 'FHIR') ? substr($className, 4) : $className;

        // List of known FHIR resources (partial list for common ones)
        $knownResources = [
            'Patient',
            'Observation',
            'Practitioner',
            'Organization',
            'Encounter',
            'Condition',
            'Procedure',
            'MedicationRequest',
            'DiagnosticReport',
            'AllergyIntolerance',
            'CarePlan',
            'Goal',
            'Immunization',
            'Location',
            'Device',
            'Medication',
            'Substance',
            'Specimen',
            'BodyStructure',
            'ImagingStudy',
            'Media',
            'DocumentReference',
            'Composition',
            'Bundle',
            'MessageHeader',
            'OperationOutcome',
            'Parameters',
            'Binary',
            'Basic',
            'DomainResource',
            'Resource',
        ];

        return in_array($name, $knownResources, true);
    }

    /**
     * Check if a class name represents a known FHIR primitive type
     *
     * @param string $className
     *
     * @return bool
     */
    private function isKnownFHIRPrimitive(string $className): bool
    {
        // Remove FHIR prefix
        $name = str_starts_with($className, 'FHIR') ? substr($className, 4) : $className;

        // List of known FHIR primitive types
        $knownPrimitives = [
            'Boolean',
            'Integer',
            'String',
            'Decimal',
            'Uri',
            'Url',
            'Canonical',
            'Base64Binary',
            'Instant',
            'Date',
            'DateTime',
            'Time',
            'Code',
            'Oid',
            'Id',
            'Markdown',
            'UnsignedInt',
            'PositiveInt',
            'Uuid',
            'Xhtml',
        ];

        return in_array($name, $knownPrimitives, true);
    }

    /**
     * Get output path for Models component files
     *
     * @param string             $version
     * @param ClassType|EnumType $type
     * @param PhpNamespace       $namespace
     *
     * @return string
     */
    private function getModelsComponentOutputPath(string $version, ClassType|EnumType $type, PhpNamespace $namespace): string
    {
        $basePath = Path::canonicalize(__DIR__ . '/../../../Models/src');

        // Extract the type category from namespace
        $namespaceParts = explode('\\', $namespace->getName());
        $typeCategory   = end($namespaceParts); // Resource, DataType, Primitive, or Enum

        // Handle backbone elements - they should go in resource subdirectories
        $typeName = $type->getName();
        if ($typeName !== null && $this->isBackboneElement($typeName, $type)) {
            $resourceName = $this->extractResourceNameFromBackboneElement($typeName, $type);
            if ($resourceName && $typeCategory === 'Resource') {
                return Path::canonicalize("{$basePath}/{$version}/Resource/{$resourceName}/{$typeName}.php");
            }
        }

        return Path::canonicalize("{$basePath}/{$version}/{$typeCategory}/{$typeName}.php");
    }

    /**
     * Check if a type represents a backbone element
     *
     * @param string             $typeName
     * @param ClassType|EnumType $type
     *
     * @return bool
     */
    private function isBackboneElement(string $typeName, ClassType|EnumType $type): bool
    {
        // Check if the class has the FHIRBackboneElement attribute
        if ($type instanceof ClassType) {
            foreach ($type->getAttributes() as $attribute) {
                if (str_contains($attribute->getName(), 'FHIRBackboneElement')) {
                    return true;
                }
            }
        }

        // Fallback: Check naming pattern for backbone elements
        // Backbone elements typically have names like FHIRPatientContact, FHIRObservationComponent
        return preg_match('/^FHIR[A-Z][a-z]+[A-Z][a-zA-Z]+$/', $typeName) === 1;
    }

    /**
     * Extract resource name from backbone element type
     *
     * @param string             $typeName
     * @param ClassType|EnumType $type
     *
     * @return string|null
     */
    private function extractResourceNameFromBackboneElement(string $typeName, ClassType|EnumType $type): ?string
    {
        // First try to get it from the FHIRBackboneElement attribute
        if ($type instanceof ClassType) {
            foreach ($type->getAttributes() as $attribute) {
                if (str_contains($attribute->getName(), 'FHIRBackboneElement')) {
                    $args = $attribute->getArguments();
                    if (isset($args['parentResource'])) {
                        return $args['parentResource'];
                    }
                }
            }
        }

        // Fallback: Extract from naming pattern using known FHIR resource names
        // Remove FHIR prefix
        $withoutPrefix = substr($typeName, 4);

        // List of known multi-word FHIR resource names (in order of length, longest first)
        $knownResources = [
            'ActivityDefinition',
            'AdverseEvent',
            'AllergyIntolerance',
            'AppointmentResponse',
            'AuditEvent',
            'BiologicallyDerivedProduct',
            'BodyStructure',
            'CapabilityStatement',
            'CarePlan',
            'CareTeam',
            'CatalogEntry',
            'ChargeItem',
            'ChargeItemDefinition',
            'ClinicalImpression',
            'CodeSystem',
            'CompartmentDefinition',
            'ConceptMap',
            'DataRequirement',
            'DetectedIssue',
            'DeviceDefinition',
            'DeviceMetric',
            'DeviceRequest',
            'DeviceUseStatement',
            'DiagnosticReport',
            'DocumentManifest',
            'DocumentReference',
            'EffectEvidenceSynthesis',
            'EligibilityRequest',
            'EligibilityResponse',
            'EnrollmentRequest',
            'EnrollmentResponse',
            'EpisodeOfCare',
            'EventDefinition',
            'EvidenceVariable',
            'ExampleScenario',
            'ExplanationOfBenefit',
            'FamilyMemberHistory',
            'GraphDefinition',
            'GuidanceResponse',
            'HealthcareService',
            'ImagingStudy',
            'ImmunizationEvaluation',
            'ImmunizationRecommendation',
            'ImplementationGuide',
            'InsurancePlan',
            'MeasureReport',
            'MedicationAdministration',
            'MedicationDispense',
            'MedicationKnowledge',
            'MedicationRequest',
            'MedicationStatement',
            'MedicinalProduct',
            'MedicinalProductAuthorization',
            'MedicinalProductContraindication',
            'MedicinalProductIndication',
            'MedicinalProductIngredient',
            'MedicinalProductInteraction',
            'MedicinalProductManufactured',
            'MedicinalProductPackaged',
            'MedicinalProductPharmaceutical',
            'MedicinalProductUndesirableEffect',
            'MessageDefinition',
            'MessageHeader',
            'MolecularSequence',
            'NamingSystem',
            'NutritionOrder',
            'OperationDefinition',
            'OperationOutcome',
            'OrganizationAffiliation',
            'PaymentNotice',
            'PaymentReconciliation',
            'PlanDefinition',
            'PractitionerRole',
            'QuestionnaireResponse',
            'RelatedPerson',
            'RequestGroup',
            'ResearchDefinition',
            'ResearchElementDefinition',
            'ResearchStudy',
            'ResearchSubject',
            'RiskAssessment',
            'RiskEvidenceSynthesis',
            'ServiceRequest',
            'SpecimenDefinition',
            'StructureDefinition',
            'StructureMap',
            'SupplyDelivery',
            'SupplyRequest',
            'TestReport',
            'TestScript',
            'ValueSet',
            'VerificationResult',
            'VisionPrescription',
            // Single word resources
            'Account',
            'Appointment',
            'Basic',
            'Binary',
            'Bundle',
            'Claim',
            'Communication',
            'Composition',
            'Condition',
            'Consent',
            'Contract',
            'Coverage',
            'Device',
            'Encounter',
            'Endpoint',
            'Evidence',
            'Flag',
            'Goal',
            'Group',
            'Immunization',
            'Invoice',
            'Library',
            'Linkage',
            'List',
            'Location',
            'Measure',
            'Media',
            'Medication',
            'Observation',
            'Organization',
            'Parameters',
            'Patient',
            'Person',
            'Practitioner',
            'Procedure',
            'Provenance',
            'Questionnaire',
            'Schedule',
            'Slot',
            'Specimen',
            'Subscription',
            'Substance',
            'Task',
        ];

        // Try to match against known resource names (longest first to avoid partial matches)
        foreach ($knownResources as $resourceName) {
            if (str_starts_with($withoutPrefix, $resourceName)) {
                return $resourceName;
            }
        }

        // Fallback to original logic if no known resource matches
        for ($i = 1; $i < strlen($withoutPrefix); ++$i) {
            if (ctype_upper($withoutPrefix[$i])) {
                return substr($withoutPrefix, 0, $i);
            }
        }

        return null;
    }

    /**
     * Generate backbone element with proper grouping
     *
     * This method is available for explicit backbone element generation when needed.
     * Currently, backbone elements are handled automatically through the output path logic.
     *
     * @param array<string, mixed> $structureDefinition
     * @param string               $version
     * @param PhpNamespace         $namespace
     *
     * @return ClassType|null
     *
     * @phpstan-ignore-next-line Method is reserved for future use
     */
    private function generateBackboneElement(array $structureDefinition, string $version, PhpNamespace $namespace): ?ClassType
    {
        // Extract parent resource and element path from the structure definition
        $elementPath    = $structureDefinition['name'];
        $pathParts      = explode('.', $elementPath);
        $parentResource = $pathParts[0];
        $elementName    = end($pathParts);

        // Generate the backbone element class
        $generator = new FHIRModelGenerator();
        $class     = $generator->generateModelClassWithErrorHandling($structureDefinition, $version, $this->errorCollector, $this->context);

        if ($class !== null) {
            // Ensure the class is properly attributed as a backbone element
            $class->addAttribute('Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement', [
                'parentResource' => $parentResource,
                'elementPath'    => $elementPath,
                'fhirVersion'    => $version,
            ]);

            // Add to the appropriate resource namespace
            $resourceNamespace = new PhpNamespace($namespace->getName() . "\\{$parentResource}");
            $resourceNamespace->add($class);

            return $class;
        }

        return null;
    }

    /**
     * Build classes for Models component, categorizing by StructureDefinition kind
     *
     * @param OutputInterface $output
     * @param string          $version
     * @param PhpNamespace    $resourceNamespace
     * @param PhpNamespace    $dataTypeNamespace
     * @param PhpNamespace    $primitiveNamespace
     *
     * @return void
     *
     * @throws \JsonException
     */
    private function buildModelsComponentClasses(OutputInterface $output, string $version, PhpNamespace $resourceNamespace, PhpNamespace $dataTypeNamespace, PhpNamespace $primitiveNamespace): void
    {
        $output->writeln('Generating Models component classes...');

        $resourceCount  = 0;
        $dataTypeCount  = 0;
        $primitiveCount = 0;

        foreach ($this->context->getDefinitions() as $structureDefinition) {
            if ($structureDefinition['resourceType'] !== 'StructureDefinition') {
                continue;
            }

            // Ignore Profiles that are constraints on other types for now
            if ($structureDefinition['kind'] === 'logical' || (isset($structureDefinition['derivation']) && $structureDefinition['derivation'] === 'constraint')) {
                continue;
            }

            $kind = $structureDefinition['kind'] ?? 'unknown';
            $name = $structureDefinition['name'] ?? 'Unknown';

            // Determine which namespace to use based on the StructureDefinition kind
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

            $output->writeln("Generating Models component class for {$name} (kind: {$kind})");
            $generator = new FHIRModelGenerator();

            $class = $generator->generateModelClassWithErrorHandling($structureDefinition, $version, $this->errorCollector, $this->context);

            if ($class !== null) {
                $this->context->addType($structureDefinition['url'], $class);
                $targetNamespace->add($class);

                // Count generated classes by type
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

            $class = $generator->generateModelClassWithErrorHandling($structureDefinition, $version, $this->errorCollector, $this->context);

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
            // Use the namespace from the ClassType itself to preserve use statements
            $elementNamespace = $type->getNamespace() ?? $this->context->getElementNamespace($version);
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

        // Manually build use statements from the namespace
        $uses = [];
        foreach ($namespace->getUses() as $alias => $original) {
            // Check if this is a simple use (no alias) or aliased use
            $shortName = substr($original, strrpos($original, '\\') + 1);
            if ($shortName === $alias) {
                $uses[] = "use {$original};";
            } else {
                $uses[] = "use {$original} as {$alias};";
            }
        }
        $usesSection = $uses ? "\n" . implode("\n", $uses) . "\n" : '';

        return <<<PHP
        <?php declare(strict_types=1);

        namespace {$namespace->getName()};{$usesSection}

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

            // Check if enum already exists to prevent duplicate generation
            if ($this->context->getEnum($url) !== null) {
                $output->writeln("Enum for {$valueset['name']} already exists, skipping generation");
                $this->context->removePendingType($url);
                $this->context->removePendingEnum($url);
                continue;
            }

            try {
                $output->writeln("Generating enum for {$valueset['name']}");

                $enumGenerator  = new FHIRValueSetGenerator();
                $classGenerator = new FHIRModelGenerator();

                $enumType = $enumGenerator->generateEnum($valueset, $version, $this->context);

                // Add enum to namespace safely
                $enumNamespace = $this->context->getEnumNamespace($version);
                $enumTypeName  = $enumType->getName();
                if ($enumTypeName !== null) {
                    // Try to add enum to namespace, handling duplicates gracefully
                    try {
                        $enumNamespace->add($enumType);
                        $this->context->addEnum($url, $enumType);
                    } catch (InvalidStateException $e) {
                        // Enum with this name already exists in namespace
                        if (str_contains($e->getMessage(), 'already exists')) {
                            $output->writeln("Enum class {$enumTypeName} already exists in namespace, skipping namespace addition");
                            // Still add to context for tracking with this URL
                            $this->context->addEnum($url, $enumType);
                        } else {
                            throw $e;
                        }
                    }
                } else {
                    $this->context->addEnum($url, $enumType);
                }

                $codeType = $classGenerator->generateModelCodeType($enumType, $version, $this->context);
                $this->context->addType($url, $codeType);
                $this->context->removePendingType($url);
                $this->context->removePendingEnum($url);

                // Add code type to DataType namespace safely (code types extend FHIRCode which is a primitive)
                $dataTypeNamespace = $this->context->getDatatypeNamespace($version);
                $codeTypeName      = $codeType->getName();
                if ($codeTypeName !== null) {
                    // Try to add code type to namespace, handling duplicates gracefully
                    try {
                        $dataTypeNamespace->add($codeType);
                    } catch (InvalidStateException $e) {
                        // Code type with this name already exists in namespace
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

        $targetNamespace    = "Ardenexal\\FHIRTools\\$namespace\\Primitive";
        $primitiveNamespace = new PhpNamespace($targetNamespace);
        $this->context->addPrimitiveNamespace($namedVersion, $primitiveNamespace);

        $targetNamespace   = "Ardenexal\\FHIRTools\\$namespace\\DataType";
        $datatypeNamespace = new PhpNamespace($targetNamespace);
        $this->context->addDatatypeNamespace($namedVersion, $datatypeNamespace);


        $this->buildElementClasses($output, $namedVersion, $elementNamespace);

        $this->buildEnumsForValuesSets($output, $namedVersion);
        $this->outputFiles($output, $namedVersion);
    }
}

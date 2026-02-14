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

    private ErrorCollector $errorCollector;

    private Filesystem $filesystem;

    /**
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
     * @param OutputInterface $output
     * @param array<string>   $packages
     * @param bool            $offlineMode
     *
     * @return int
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
     * @param array<string> $packages
     */
    private function executeGeneration(OutputInterface $output, array $packages, bool $offlineMode = false): int
    {
        $output->writeln('<info>Generating FHIR models...</info>');

        $this->clearOutputDirectory($output);

        $loadingPackagesIndicator = new ProgressIndicator($output);
        $loadingPackagesIndicator->start('Loading FHIR Implementation Guide packages...');

        if (! in_array(self::DEFAULT_TERMINOLOGY_PACKAGE, $packages, true)) {
            array_unshift($packages, self::DEFAULT_TERMINOLOGY_PACKAGE);
        }

        foreach ($packages as $package) {
            $packageParts = explode('#', $package);
            $version      = $packageParts[1] ?? null;
            $package      = $packageParts[0];

            $loadingPackagesIndicator->setMessage('Loading package ' . $package . ($version ? " version $version" : ''));

            try {
                $this->processPackage($output, $package, $version, $offlineMode);
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
                        'line'            => $e->getLine(),
                        'file'            => $e->getFile(),
                    ],
                );
                $output->writeln("<error>Failed to process package {$package}: {$e->getMessage()}</error>");
            }

            $loadingPackagesIndicator->advance();
        }

        $loadingPackagesIndicator->finish('Finished loading FHIR Implementation Guide packages.');

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

            match ($fhirVersion) {
                'R4', 'R4B', 'R5' => null,
                default           => throw PackageException::unsupportedFhirVersion($fhirVersion, ['R4', 'R4B', 'R5']),
            };

            $this->context[$fhirVersion]->loadDefinitions($definitions);
            $this->generateClassesForPackage($output, $package, $fhirVersion);
        }
    }

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
     * @throws \JsonException
     */
    private function generateClassesForPackage(OutputInterface $output, string $package, string $fhirVersion): void
    {
        $baseNamespace = "Ardenexal\\FHIRTools\\Component\\Models\\{$fhirVersion}";

        $resourceNamespace  = new PhpNamespace("{$baseNamespace}\\Resource");
        $dataTypeNamespace  = new PhpNamespace("{$baseNamespace}\\DataType");
        $primitiveNamespace = new PhpNamespace("{$baseNamespace}\\Primitive");
        $enumNamespace      = new PhpNamespace("{$baseNamespace}\\Enum");

        $this->context[$fhirVersion]->addElementNamespace($fhirVersion, $resourceNamespace);
        $this->context[$fhirVersion]->addEnumNamespace($fhirVersion, $enumNamespace);
        $this->context[$fhirVersion]->addPrimitiveNamespace($fhirVersion, $primitiveNamespace);
        $this->context[$fhirVersion]->addDatatypeNamespace($fhirVersion, $dataTypeNamespace);

        $this->buildClasses($output, $fhirVersion, $resourceNamespace, $dataTypeNamespace, $primitiveNamespace);

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

        $this->outputGeneratedFiles($output, $fhirVersion);
    }

    /**
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

            if ($structureDefinition['kind'] === 'logical' || (isset($structureDefinition['derivation']) && $structureDefinition['derivation'] === 'constraint')) {
                continue;
            }

            $kind = $structureDefinition['kind'] ?? 'unknown';
            $name = $structureDefinition['name'] ?? 'Unknown';

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

                $enumType = $enumGenerator->generateEnum($valueset, $version, $this->context[$version]);

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

        $className = $type->getName();
        if ($className !== null) {
            if ($this->isKnownFHIRResource($className)) {
                return new PhpNamespace("{$baseNamespace}\\Resource");
            }

            if ($this->isKnownFHIRPrimitive($className)) {
                return new PhpNamespace("{$baseNamespace}\\Primitive");
            }
        }

        return new PhpNamespace("{$baseNamespace}\\DataType");
    }

    private function isKnownFHIRResource(string $className): bool
    {
        $name = str_starts_with($className, 'FHIR') ? substr($className, 4) : $className;

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

    private function isKnownFHIRPrimitive(string $className): bool
    {
        $name = str_starts_with($className, 'FHIR') ? substr($className, 4) : $className;

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

    private function getOutputPath(string $version, ClassType|EnumType $type, PhpNamespace $namespace): string
    {
        $basePath = Path::canonicalize(__DIR__ . '/../../../Models/src');

        $namespaceParts = explode('\\', $namespace->getName());
        $typeCategory   = end($namespaceParts);

        $typeName = $type->getName();
        if ($typeName !== null && $this->isBackboneElement($typeName, $type)) {
            $resourceName = $this->extractResourceNameFromBackboneElement($typeName, $type);
            if ($resourceName && $typeCategory === 'Resource') {
                return Path::canonicalize("{$basePath}/{$version}/Resource/{$resourceName}/{$typeName}.php");
            }
        }

        return Path::canonicalize("{$basePath}/{$version}/{$typeCategory}/{$typeName}.php");
    }

    private function isBackboneElement(string $typeName, ClassType|EnumType $type): bool
    {
        if ($type instanceof ClassType) {
            foreach ($type->getAttributes() as $attribute) {
                if (str_contains($attribute->getName(), 'FHIRBackboneElement')) {
                    return true;
                }
            }
        }

        return preg_match('/^FHIR[A-Z][a-z]+[A-Z][a-zA-Z]+$/', $typeName) === 1;
    }

    private function extractResourceNameFromBackboneElement(string $typeName, ClassType|EnumType $type): ?string
    {
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

        $withoutPrefix = substr($typeName, 4);

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

        foreach ($knownResources as $resourceName) {
            if (str_starts_with($withoutPrefix, $resourceName)) {
                return $resourceName;
            }
        }

        for ($i = 1, $iMax = strlen($withoutPrefix); $i < $iMax; ++$i) {
            if (ctype_upper($withoutPrefix[$i])) {
                return substr($withoutPrefix, 0, $i);
            }
        }

        return null;
    }

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

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Command;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ClassNameResolver;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRExtensionGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIROperationGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRProfileGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRValueSetGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\LogicalModelGenerator;
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
 * The generation flow works in three global phases:
 *
 *  1. **Load** — Download/cache every requested FHIR package and parse its StructureDefinitions
 *     into the version-specific BuilderContext. Collects the set of affected FHIR versions.
 *  2. **Clear** — Remove only the output directories for the affected FHIR versions (e.g. R4/).
 *     Unaffected versions (e.g. R4B/) are left untouched, so separate invocations don't clobber
 *     each other's output.
 *  3. **Generate** — For each affected FHIR version, convert StructureDefinitions into PHP classes
 *     (via Nette PhpGenerator), build enums for referenced ValueSets, and write all files to
 *     `src/Component/Models/src/{version}/`.
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
     * The HL7 terminology packages contain CodeSystem and ValueSet definitions shared
     * across all FHIR versions. The correct version-specific package is prepended
     * automatically based on the core packages being generated.
     *
     * @var array<string, string>
     */
    private const array TERMINOLOGY_PACKAGES = [
        'r4'  => 'hl7.terminology.r4#7.0.0',
        'r4b' => 'hl7.terminology.r4b#6.0.2',
        'r5'  => 'hl7.terminology.r5#7.0.0',
    ];

    /**
     * Base namespace for the separate CDA models package (ardenexal/fhir-cda-models, ADR-009).
     */
    private const string CDA_BASE_NAMESPACE = 'Ardenexal\\FHIRTools\\Component\\CdaModels';

    /**
     * Canonical URL of the CDA InfrastructureRoot base type. Any logical model whose
     * baseDefinition chain reaches this is an act/role/entity class (→ ClinicalClass\).
     */
    private const string CDA_INFRASTRUCTURE_ROOT_URL = 'http://hl7.org/cda/stds/core/StructureDefinition/InfrastructureRoot';

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
            'hl7.fhir.uv.extensions.r4#5.2.0',
        ],
        'R4B' => [
            'hl7.terminology.r4b#6.0.2',
            'hl7.fhir.r4b.core#4.3.0',
            'hl7.fhir.uv.extensions.r4b#5.2.0',
        ],
        'R5' => [
            'hl7.terminology.r5#7.0.0',
            'hl7.fhir.r5.core#5.0.0',
            'hl7.fhir.uv.extensions.r5#5.2.0',
        ],
    ];

    /** Collects non-fatal errors and warnings during generation for reporting at the end. */
    private ErrorCollector $errorCollector;

    private Filesystem $filesystem;

    /**
     * One BuilderContext per FHIR version (or pseudo-version for logical model IGs).
     * Each context holds the loaded StructureDefinitions, generated classes, pending enums,
     * and namespace registrations for that version.
     *
     * 'CDA' is a pseudo-version key for CDA logical model packages (hl7.cda.*, au.digitalhealth.cda.*).
     * CDA packages report fhirVersion 5.0.0 but must be isolated from the R5 context because
     * CDA types and FHIR R5 types share no namespace and must not cross-reference each other.
     *
     * @var array{
     *  R4: BuilderContext,
     *  R4B: BuilderContext,
     *  R5:  BuilderContext,
     *  CDA: BuilderContext
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
            'CDA' => new BuilderContext(),
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
        array $packages = self::DEFAULT_IG_PACKAGES['R4'],
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
     * Main orchestration method — loads all packages, clears only affected version directories,
     * then generates classes. Separating load from generate ensures we only wipe version
     * directories that are actually being regenerated.
     *
     * @param array<string> $packages
     */
    private function executeGeneration(OutputInterface $output, array $packages, bool $offlineMode = false): int
    {
        $output->writeln('<info>Generating FHIR models...</info>');

        // The terminology packages define shared CodeSystems and ValueSets (e.g. AdministrativeGender).
        // Detect which FHIR versions are being generated and prepend the matching terminology packages.
        $packages = $this->ensureTerminologyPackages($packages);

        // --- Phase 1: Load all packages, collecting affected FHIR versions ---
        $loadingPackagesIndicator = new ProgressIndicator($output);
        $loadingPackagesIndicator->start('Loading FHIR Implementation Guide packages...');

        $affectedVersions = [];

        foreach ($packages as $package) {
            // Packages are specified as "name#version", e.g. "hl7.fhir.r4.core#4.0.1"
            $packageParts = explode('#', $package);
            $version      = $packageParts[1] ?? null;
            $package      = $packageParts[0];

            $loadingPackagesIndicator->setMessage('Loading package ' . $package . ($version ? " version $version" : ''));

            try {
                $versions         = $this->loadPackage($package, $version, $offlineMode);
                $affectedVersions = array_unique(array_merge($affectedVersions, $versions));
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

        // --- Phase 2: Clear only the directories for affected FHIR versions ---
        $this->clearOutputDirectory($output, $affectedVersions);

        // --- Phase 3: Generate classes for each affected FHIR version ---
        foreach ($affectedVersions as $fhirVersion) {
            $this->generateClassesForPackage($output, $fhirVersion);
        }

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
     * Download/cache a single FHIR package and load its definitions into the version-specific
     * BuilderContext. Returns the FHIR versions that were loaded (e.g. ['R4']).
     *
     * Intentionally does NOT generate classes — that happens in a separate phase after all
     * packages are loaded, so we know which version directories to clear before writing.
     *
     * Only versions where the package contributes at least one StructureDefinition are
     * returned as "affected". Terminology-only packages (CodeSystems, ValueSets) are loaded
     * into context so enum generation can resolve bindings, but they do not own a version
     * output directory and must not trigger clearing of one.
     *
     * @return array<string>
     */
    private function loadPackage(string $package, ?string $version, bool $offlineMode): array
    {
        $packageMetaData = $this->packageLoader->installPackage(
            packageName: $package,
            version: $version,
            registry: null,
            resolveDeps: false,
            offlineMode: $offlineMode,
        );

        // Load definitions once — the same set applies to all FHIR versions of this package.
        $definitions = $this->packageLoader->loadPackageStructureDefinitions($packageMetaData);

        // CDA packages (hl7.cda.*, au.digitalhealth.cda.*) are routed to the dedicated CDA
        // context by package name, not by fhirVersion. Both CDA and FHIR R5 report
        // fhirVersion: 5.0.0, so the package name is the only reliable discriminant.
        if ($this->isCdaPackage($package)) {
            return $this->loadCdaPackageDefinitions($definitions);
        }

        // Determine upfront whether this package contributes any generatable StructureDefinitions.
        // We mirror the same filter used in buildClasses: skip logical models and constraint
        // derivations (e.g. Extension profiles in the terminology package). A package that only
        // contributes constraints or CodeSystems/ValueSets must not trigger a directory clear.
        $hasGeneratableStructureDefinitions = false;
        foreach ($definitions as $def) {
            if (($def['resourceType'] ?? '') !== 'StructureDefinition') {
                continue;
            }
            if (($def['kind'] ?? '') === 'logical' || ($def['derivation'] ?? '') === 'constraint') {
                continue;
            }
            $hasGeneratableStructureDefinitions = true;
            break;
        }

        $affectedVersions = [];

        foreach ($packageMetaData->getFhirVersions() as $fhirVersion) {
            // Validate the FHIR version is one we support
            match ($fhirVersion) {
                'R4', 'R4B', 'R5' => null,
                default           => throw PackageException::unsupportedFhirVersion($fhirVersion, ['R4', 'R4B', 'R5']),
            };

            $this->context[$fhirVersion]->loadDefinitions($definitions);

            if ($hasGeneratableStructureDefinitions) {
                $affectedVersions[] = $fhirVersion;
            }
        }

        return $affectedVersions;
    }

    /**
     * Load CDA package definitions into the dedicated CDA BuilderContext.
     * Returns ['CDA'] if the package contains generatable logical model SDs,
     * or [] if the package contains only ValueSets/CodeSystems (terminology-only).
     *
     * @param array<array<string, mixed>> $definitions
     *
     * @return array<string>
     */
    private function loadCdaPackageDefinitions(array $definitions): array
    {
        // First-wins for duplicate canonical URLs: the AU package bundles structurally-identical
        // copies of the core CDA StructureDefinitions AND ValueSets under the same URLs (e.g.
        // CDAActClass). Skipping any resource whose URL is already loaded keeps the pinned
        // hl7.cda.uv.core authoritative for both core classes and core terminology (callers load
        // core before AU) and guards against a future AU build drifting from it — the AU package
        // then contributes only its own AU-namespace additions (M4 decision).
        $existing = $this->context['CDA']->getDefinitions();
        $filtered = [];
        foreach ($definitions as $url => $def) {
            if (isset($existing[$url])) {
                continue;
            }
            $filtered[$url] = $def;
        }

        $this->context['CDA']->loadDefinitions($filtered);

        foreach ($filtered as $def) {
            if (($def['resourceType'] ?? '') !== 'StructureDefinition') {
                continue;
            }
            if (($def['kind'] ?? '') === 'logical' && ($def['derivation'] ?? '') === 'specialization') {
                return ['CDA'];
            }
        }

        return [];
    }

    /**
     * Return true if the package name belongs to a CDA logical model IG.
     * Routing is by name prefix because CDA and FHIR R5 both report fhirVersion: 5.0.0.
     */
    private function isCdaPackage(string $packageName): bool
    {
        return str_starts_with($packageName, 'hl7.cda.')
            || str_starts_with($packageName, 'au.digitalhealth.cda.');
    }

    /**
     * Detect which FHIR versions are represented in the package list and prepend
     * the matching terminology packages if not already present.
     *
     * Matches on the FHIR version segment in standard HL7 package names
     * (e.g. "hl7.fhir.r5.core" → prepend R5 terminology). Falls back to R4
     * terminology for packages whose names don't match a known version pattern.
     *
     * CDA packages are excluded from this check — CDA has its own ValueSets
     * (NullFlavor, ActClass, etc.) bundled in the CDA package itself and does
     * not depend on HL7 FHIR terminology packages.
     *
     * @param array<string> $packages
     *
     * @return array<string>
     */
    private function ensureTerminologyPackages(array $packages): array
    {
        // Strip the version suffix for name-only matching
        $packageNames = array_map(static fn (string $p) => explode('#', $p)[0], $packages);

        // If every package in the list is a CDA package, skip FHIR terminology injection entirely.
        $allCda = $packageNames !== [] && array_reduce(
            $packageNames,
            fn (bool $carry, string $name): bool => $carry && $this->isCdaPackage($name),
            true,
        );
        if ($allCda) {
            return $packages;
        }

        $needed = [];

        foreach (self::TERMINOLOGY_PACKAGES as $version => $terminologyPackage) {
            // Skip if the terminology package is already in the list
            $packageName    = explode('#', $terminologyPackage)[0];
            $alreadyPresent = array_filter($packages, static fn (string $p) => str_starts_with($p, $packageName));
            if ($alreadyPresent !== []) {
                continue;
            }

            // Check if any non-CDA package targets this FHIR version (e.g. ".r5." in "hl7.fhir.r5.core")
            $versionSegment = ".{$version}.";
            foreach ($packages as $package) {
                $name = explode('#', $package)[0];
                if (!$this->isCdaPackage($name) && str_contains(strtolower($package), $versionSegment)) {
                    $needed[] = $terminologyPackage;
                    break;
                }
            }
        }

        // If no version-specific terminology was detected (and there are non-CDA packages),
        // fall back to R4 terminology.
        $hasNonCdaPackages = array_filter($packageNames, fn (string $name): bool => !$this->isCdaPackage($name)) !== [];
        if ($needed === [] && $hasNonCdaPackages) {
            $needed[] = self::TERMINOLOGY_PACKAGES['r4'];
        }

        // Prepend all needed terminology packages (before the core packages)
        array_unshift($packages, ...$needed);

        return $packages;
    }

    /**
     * Remove previously generated directories for the specified FHIR versions only.
     *
     * By scoping the clear to only the versions being regenerated, separate invocations
     * for different FHIR versions (e.g. R4 then R4B) will not clobber each other's output.
     *
     * @param array<string> $versions FHIR version identifiers to clear, e.g. ['R4', 'R4B']
     */
    private function clearOutputDirectory(OutputInterface $output, array $versions): void
    {
        $basePath = Path::canonicalize(__DIR__ . '/../../../Models/src');

        if ($this->filesystem->exists($basePath)) {
            $output->writeln('<comment>Clearing existing output directory...</comment>');

            foreach ($versions as $versionName) {
                $versionDir = $basePath . '/' . $versionName;
                if ($this->filesystem->exists($versionDir)) {
                    $output->writeln("<comment>Clearing {$versionName} directory...</comment>");
                    $this->filesystem->remove($versionDir);
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
    private function generateClassesForPackage(OutputInterface $output, string $fhirVersion): void
    {
        if ($fhirVersion === 'CDA') {
            $this->generateCdaPackage($output);

            return;
        }

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
                $fhirVersion,
                'ENUM_GENERATION_PARTIAL_FAILURE',
                'warning',
                [
                    'exception_class' => get_class($e),
                    'fhir_version'    => $fhirVersion,
                ],
            );
            $output->writeln('<comment>Warning: Enum generation failed but continuing with class generation</comment>');
        }

        // Phase 3: Generate base FHIR extensions (derivation=constraint, type=Extension).
        // These are written to Models/src/{version}/Extension/ so that the IG generator
        // can reference them as pre-built parent classes.
        $this->buildExtensions($output, $fhirVersion);

        // Phase 4: Generate base FHIR profiles (derivation=constraint, non-extension).
        // These are written to Models/src/{version}/Profile/ and serve the same purpose.
        $this->buildProfiles($output, $fhirVersion);

        // Phase 5: Generate typed operation payloads and holders from OperationDefinitions.
        // Written to Models/src/{version}/Operation/. Runs after the type phases because the
        // generator reads StructureDefinition `kind` and `baseDefinition` out of the same context
        // to classify output shapes and order value[x] variants.
        $this->buildOperations($output, $fhirVersion);

        // Phase 6: Write all generated classes and enums to disk
        $this->outputGeneratedFiles($output, $fhirVersion);
    }

    /**
     * Generate the CDA logical-model classes (kind=logical, derivation=specialization) from the
     * loaded CDA package into the separate `ardenexal/fhir-cda-models` package (ADR-009).
     *
     * Classes are split into two namespaces: the V3 datatype lattice → CdaModels\DataType\, and the
     * act/role/entity classes (InfrastructureRoot + descendants, plus ClinicalDocument) →
     * CdaModels\ClinicalClass\. Parent and property types are resolved from a pre-computed
     * url → FQCN map so generation order is irrelevant (CDA references every type by canonical URL).
     */
    private function generateCdaPackage(OutputInterface $output): void
    {
        $context         = $this->context['CDA'];
        $dataTypeNs      = new PhpNamespace(self::CDA_BASE_NAMESPACE . '\\DataType');
        $clinicalClassNs = new PhpNamespace(self::CDA_BASE_NAMESPACE . '\\ClinicalClass');

        // Collect the generatable logical models, keyed by canonical URL.
        $definitions = [];
        foreach ($context->getDefinitions() as $sd) {
            if (($sd['resourceType'] ?? '') !== 'StructureDefinition') {
                continue;
            }
            if (($sd['kind'] ?? '') !== 'logical' || ($sd['derivation'] ?? '') !== 'specialization') {
                continue;
            }
            $url = (string) ($sd['url'] ?? '');
            if ($url !== '') {
                $definitions[$url] = $sd;
            }
        }

        // Pre-pass: parent maps, names, directly-declared xml namespaces, own property/constraint
        // sets. Two parent notions are tracked: `baseOf` (raw `baseDefinition`, drives the
        // xml-namespace inheritance walk) and `parentOf` (the EFFECTIVE PHP parent — `type` when it
        // names a *different* generatable class, else `baseDefinition`). AU specializations set
        // `type` to the core class they refine while `baseDefinition` points at the abstract root
        // (ANY / InfrastructureRoot), so clinical-vs-datatype routing and inherited-member skipping
        // must follow `parentOf`, matching {@see LogicalModelGenerator}'s `extends` rule. For core
        // SDs `parentOf == baseOf` (type==url, or the 12 separator-mismatch cases that don't name a
        // different generatable class), so core generation is unaffected.
        $names           = [];
        $baseOf          = [];
        $parentOf        = [];
        $xmlNsDirect     = [];
        $ownPropNames    = [];
        $ownConstraints  = [];
        foreach ($definitions as $url => $sd) {
            $base                  = isset($sd['baseDefinition']) ? (string) $sd['baseDefinition'] : null;
            $type                  = (string) ($sd['type'] ?? '');
            $names[$url]           = (string) ($sd['name'] ?? '');
            $baseOf[$url]          = $base;
            $parentOf[$url]        = ($type !== '' && $type !== $url && isset($definitions[$type])) ? $type : $base;
            $xmlNsDirect[$url]     = $this->readXmlNamespace($sd);
            $ownPropNames[$url]    = $this->cdaDirectPropertyNames($sd);
            $ownConstraints[$url]  = $this->cdaConstraintKeys($sd);
        }

        $urlToFqcn  = [];
        $urlToNs    = [];
        foreach ($definitions as $url => $sd) {
            $name             = $names[$url];
            $isClinical       = $this->cdaIsClinicalClass($url, $parentOf, $names);
            $namespace        = $isClinical ? $clinicalClassNs : $dataTypeNs;
            $urlToNs[$url]    = $namespace;
            $urlToFqcn[$url]  = '\\' . $namespace->getName() . '\\' . ClassNameResolver::logicalModelClassName($url, $name);
        }

        // Clear and regenerate the CDA output directories.
        $this->clearCdaOutputDirectories($output);

        // Generate enums for every ValueSet bundled in the CDA package and build a
        // valueSet-URL → enum-FQCN map. Enums are generated before classes so coded properties
        // can be typed to them during class generation. (Generation is driven by the bundled
        // ValueSets, not by bindings: several CDA ValueSets — e.g. CDAActMood, CDARoleClass — are
        // referenced only through fixed coded attributes and would otherwise be missed.)
        $valueSetToEnumFqcn = $this->generateCdaEnums($output);

        $generator = new LogicalModelGenerator();

        // Pre-compute each class's OWN constructor parameters, then memoise each class's FULL ordered
        // parameter list (own ++ parent's full, walked through `parentOf`). A child is handed its
        // parent's full list so it can re-declare those params and forward them via
        // parent::__construct() — without which inherited promoted properties are never initialised
        // (PHP runs no parent constructor implicitly) and throw on access.
        $ownParams = [];
        foreach ($definitions as $url => $sd) {
            $parent          = $parentOf[$url] ?? '';
            $inheritedNames  = ($parent !== '' && isset($ownPropNames[$parent])) ? $ownPropNames[$parent] : [];
            $classXmlNs      = $this->resolveCdaXmlNamespace($url, $xmlNsDirect, $baseOf);
            $ownParams[$url] = $generator->collectOwnParameters($sd, $urlToFqcn, $classXmlNs, $inheritedNames, $valueSetToEnumFqcn);
        }

        /** @var array<string, list<array<string, mixed>>> $fullParams */
        $fullParams = [];
        foreach (array_keys($definitions) as $url) {
            $this->resolveFullCdaParameters((string) $url, $parentOf, $ownParams, $fullParams);
        }

        $generated = 0;
        foreach ($definitions as $url => $sd) {
            $namespace    = $urlToNs[$url];
            $xmlNamespace = $this->resolveCdaXmlNamespace($url, $xmlNsDirect, $baseOf);
            // Properties/constraints declared by the EFFECTIVE parent (whose flattened snapshot
            // already includes everything it inherits) are skipped here and come via `extends`.
            // Uses `parentOf` (type-wins-else-baseDefinition) so an AU specialization skips the
            // members of the core class it extends — not just those of its abstract `baseDefinition`
            // root — which would otherwise re-declare the whole inherited surface.
            $parent                  = $parentOf[$url] ?? '';
            $inheritedNames          = ($parent !== '' && isset($ownPropNames[$parent])) ? $ownPropNames[$parent] : [];
            $inheritedConstraintKeys = ($parent !== '' && isset($ownConstraints[$parent])) ? $ownConstraints[$parent] : [];
            $inheritedParams         = ($parent !== '' && isset($fullParams[$parent])) ? $fullParams[$parent] : [];
            try {
                $class = $generator->generate($sd, $namespace, $xmlNamespace, $urlToFqcn, $inheritedNames, $inheritedConstraintKeys, $valueSetToEnumFqcn, $inheritedParams);
            } catch (\Throwable $e) {
                $this->errorCollector->addError(
                    "CDA class generation failed for {$url}: {$e->getMessage()}",
                    'CDA',
                    'CDA_CLASS_GENERATION_FAILURE',
                    'error',
                    ['exception_class' => get_class($e), 'url' => $url],
                );

                continue;
            }
            $context->addType($url, $namespace->getName(), $class);
            ++$generated;
        }

        $this->outputCdaFiles($output);
        $output->writeln("<info>Generated {$generated} CDA logical model classes</info>");

        if ($this->errorCollector->hasErrors()) {
            $output->writeln('<error>' . $this->errorCollector->getSummary() . '</error>');
            if ($output->isVerbose()) {
                $output->writeln($this->errorCollector->getDetailedOutput());
            }
        }
    }

    /**
     * Generate a PHP enum for every ValueSet bundled in the CDA package and register the CDA enum
     * namespace (`Ardenexal\FHIRTools\Component\CdaModels\Enum`). Returns a map of
     * canonical ValueSet URL → leading-backslash enum FQCN so coded properties can be typed to the
     * enum during class generation.
     *
     * The bundled CDA ValueSets inline their concepts (`compose.include[].concept`), so no external
     * `hl7.terminology.*` package is required — the existing {@see FHIRValueSetGenerator} consumes
     * them via its inline-concept path. Enum class names drop the redundant `CDA` prefix
     * ({@see ClassNameResolver::cdaEnumClassName()}); the same stripped name feeds both the emitted
     * file and this map so the property type and the generated enum agree.
     *
     * @return array<string, string> ValueSet URL → enum FQCN (leading backslash)
     */
    private function generateCdaEnums(OutputInterface $output): array
    {
        $context       = $this->context['CDA'];
        $enumNamespace = new PhpNamespace(self::CDA_BASE_NAMESPACE . '\\Enum');
        $context->addEnumNamespace('CDA', $enumNamespace);

        $enumGenerator      = new FHIRValueSetGenerator();
        $valueSetToEnumFqcn = [];
        foreach ($context->getDefinitions() as $valueSet) {
            if (($valueSet['resourceType'] ?? '') !== 'ValueSet') {
                continue;
            }
            $url  = (string) ($valueSet['url'] ?? '');
            $name = (string) ($valueSet['name'] ?? '');
            if ($url === '' || $name === '') {
                continue;
            }

            $className = ClassNameResolver::cdaEnumClassName($url, $name);
            try {
                $enumType = $enumGenerator->generateEnum($valueSet, 'CDA', $context, $className);
            } catch (\Throwable $e) {
                $this->errorCollector->addError(
                    "CDA enum generation failed for {$url}: {$e->getMessage()}",
                    'CDA',
                    'CDA_ENUM_GENERATION_FAILURE',
                    'error',
                    ['exception_class' => get_class($e), 'url' => $url],
                );

                continue;
            }

            $context->addEnum($url, $enumNamespace->getName(), $enumType);
            $valueSetToEnumFqcn[$url] = '\\' . $enumNamespace->getName() . '\\' . $className;
            $output->writeln("Generated CDA enum {$className}");
        }

        return $valueSetToEnumFqcn;
    }

    /**
     * Collect the direct-child (depth-1) property names of a StructureDefinition's flattened
     * snapshot. Because the snapshot inlines inherited elements, a type's set is a superset of its
     * parent's — used to skip inherited properties when generating a subclass.
     *
     * @param array<string, mixed> $sd
     *
     * @return list<string>
     */
    private function cdaDirectPropertyNames(array $sd): array
    {
        $names    = [];
        $elements = $sd['snapshot']['element'] ?? [];
        if (!is_array($elements)) {
            return [];
        }
        foreach ($elements as $element) {
            if (!is_array($element)) {
                continue;
            }
            $path = (string) ($element['path'] ?? '');
            if (substr_count($path, '.') !== 1) {
                continue;
            }
            $name = LogicalModelGenerator::propertyNameFromPath($path);
            if ($name !== '' && !in_array($name, $names, true)) {
                $names[] = $name;
            }
        }

        return $names;
    }

    /**
     * Collect the root-element constraint keys (those with a FHIRPath `expression`) of a
     * StructureDefinition's flattened snapshot. Used to skip inherited invariants on subclasses,
     * since CDA inlines a parent's constraints into each child's snapshot.
     *
     * @param array<string, mixed> $sd
     *
     * @return list<string>
     */
    private function cdaConstraintKeys(array $sd): array
    {
        $keys        = [];
        $rootElement = $sd['snapshot']['element'][0] ?? null;
        if (!is_array($rootElement)) {
            return [];
        }
        $constraints = $rootElement['constraint'] ?? [];
        if (!is_array($constraints)) {
            return [];
        }
        foreach ($constraints as $constraint) {
            if (!is_array($constraint)) {
                continue;
            }
            $key = (string) ($constraint['key'] ?? '');
            if ($key !== '' && (string) ($constraint['expression'] ?? '') !== '' && !in_array($key, $keys, true)) {
                $keys[] = $key;
            }
        }

        return $keys;
    }

    /**
     * Read the urn:hl7-org:v3 XML namespace from the FHIR tooling xml-namespace extension on a
     * StructureDefinition, or null if the SD does not carry it directly.
     *
     * @param array<string, mixed> $sd
     */
    private function readXmlNamespace(array $sd): ?string
    {
        $extensions = $sd['extension'] ?? [];
        if (!is_array($extensions)) {
            return null;
        }
        foreach ($extensions as $extension) {
            if (!is_array($extension)) {
                continue;
            }
            if (str_contains((string) ($extension['url'] ?? ''), 'xml-namespace')) {
                $value = $extension['valueUri'] ?? $extension['valueString'] ?? null;

                return $value !== null ? (string) $value : null;
            }
        }

        return null;
    }

    /**
     * Resolve a type's XML namespace: its own declaration, else the nearest ancestor's via the
     * baseDefinition chain, else the CDA default (urn:hl7-org:v3). A non-null value is guaranteed so
     * the serializer never wrongly permits JSON for a CDA class (1/124 SDs lacks a direct value).
     *
     * @param array<string, string|null> $xmlNsDirect
     * @param array<string, string|null> $baseOf
     */
    private function resolveCdaXmlNamespace(string $url, array $xmlNsDirect, array $baseOf): string
    {
        $seen    = [];
        $current = $url;
        while ($current !== '' && !isset($seen[$current])) {
            $seen[$current] = true;
            if (($xmlNsDirect[$current] ?? null) !== null) {
                return (string) $xmlNsDirect[$current];
            }
            $current = $baseOf[$current] ?? '';
        }

        return 'urn:hl7-org:v3';
    }

    /**
     * Resolve a class's FULL ordered constructor-parameter list (own ++ parent's full), memoised
     * through the `parentOf` chain so it is order-independent. A class is handed its parent's full
     * list as the set to re-declare and forward via parent::__construct(). Cycle-safe via a
     * placeholder written before recursing.
     *
     * @param array<string, string|null>                $parentOf
     * @param array<string, list<array<string, mixed>>> $ownParams
     * @param array<string, list<array<string, mixed>>> $memo      Accumulates results (by reference)
     *
     * @return list<array<string, mixed>>
     */
    private function resolveFullCdaParameters(string $url, array $parentOf, array $ownParams, array &$memo): array
    {
        if (array_key_exists($url, $memo)) {
            return $memo[$url];
        }
        $memo[$url] = []; // cycle guard
        $parent     = $parentOf[$url] ?? '';
        $inherited  = ($parent !== '' && isset($ownParams[$parent]))
            ? $this->resolveFullCdaParameters($parent, $parentOf, $ownParams, $memo)
            : [];

        return $memo[$url] = array_merge($ownParams[$url] ?? [], $inherited);
    }

    /**
     * Decide whether a CDA logical model is a ClinicalClass (act/role/entity) rather than a V3
     * datatype, by walking its EFFECTIVE-parent chain (`parentOf` = type-wins-else-baseDefinition).
     * Clinical iff the chain reaches `InfrastructureRoot` (the act/role/entity root) or a node named
     * `ClinicalDocument` (which extends `ANY`, not `InfrastructureRoot`, so it is matched by name).
     *
     * Walking `parentOf` makes AU specializations inherit their core parent's classification:
     * `au-ClinicalDocument` → `ClinicalDocument` (clinical), `au-Act` → `Act` → `InfrastructureRoot`
     * (clinical), `addr` → `AD` (datatype). Cycle-safe. For core SDs this is equivalent to the
     * previous name-or-`baseDefinition`-chain rule, so core routing is unchanged.
     *
     * @param array<string, string|null> $parentOf
     * @param array<string, string>      $names
     */
    private function cdaIsClinicalClass(string $url, array $parentOf, array $names): bool
    {
        $seen    = [];
        $current = $url;
        while ($current !== '' && !isset($seen[$current])) {
            if (($names[$current] ?? '') === 'ClinicalDocument') {
                return true;
            }
            if ($current === self::CDA_INFRASTRUCTURE_ROOT_URL) {
                return true;
            }
            $seen[$current] = true;
            $current        = $parentOf[$current] ?? '';
        }

        return false;
    }

    /**
     * Remove the previously generated CDA output directories so a regen does not leave stale files.
     */
    private function clearCdaOutputDirectories(OutputInterface $output): void
    {
        $basePath = Path::canonicalize(__DIR__ . '/../../../CdaModels/src');
        foreach (['DataType', 'ClinicalClass', 'Enum'] as $category) {
            $dir = "{$basePath}/{$category}";
            if ($this->filesystem->exists($dir)) {
                $output->writeln("<comment>Clearing CDA {$category} directory...</comment>");
                $this->filesystem->remove($dir);
            }
        }
    }

    /**
     * Write the generated CDA classes to src/Component/CdaModels/src/{DataType,ClinicalClass}/.
     */
    private function outputCdaFiles(OutputInterface $output): void
    {
        $basePath = Path::canonicalize(__DIR__ . '/../../../CdaModels/src');

        foreach ($this->context['CDA']->getTypes() as $type) {
            if (!$type->isClass()) {
                continue;
            }
            $namespaceParts  = explode('\\', $type->namespace);
            $category        = end($namespaceParts);
            $className       = $type->getClassName();
            $contents        = self::asPhpFile($type->asClassType(), $type->namespace);
            $outputPath      = Path::canonicalize("{$basePath}/{$category}/{$className}.php");

            $directory = dirname($outputPath);
            if (!$this->filesystem->exists($directory)) {
                $this->filesystem->mkdir($directory, 0755);
            }
            $this->filesystem->dumpFile($outputPath, $contents);
            $output->writeln("Generated CDA class {$className}");
        }

        foreach ($this->context['CDA']->getEnums() as $type) {
            $className   = $type->getClassName();
            $contents    = self::asPhpFile($type->asEnumType(), $type->namespace);
            $outputPath  = Path::canonicalize("{$basePath}/Enum/{$className}.php");

            $directory = dirname($outputPath);
            if (!$this->filesystem->exists($directory)) {
                $this->filesystem->mkdir($directory, 0755);
            }
            $this->filesystem->dumpFile($outputPath, $contents);
            $output->writeln("Generated CDA enum {$className}");
        }
    }

    /**
     * Generate typed IN/OUT payload classes and an invocation holder per OperationDefinition.
     *
     * Definitions reach the context via `PackageLoader`, which admits `OperationDefinition`
     * alongside ValueSet and CodeSystem. Unlike the type phases this iterates `getDefinitions()`
     * directly, because operations are not StructureDefinitions.
     *
     * `kind = 'query'` is filtered **here**, at the call site, as well as inside the generator's
     * `canGenerate()`. That duplication is deliberate: nothing in this pipeline dispatches on
     * `canGenerate()` — every generator is invoked explicitly — so a filter living only there would
     * silently do nothing.
     */
    private function buildOperations(OutputInterface $output, string $version): void
    {
        $generator = new FHIROperationGenerator();
        $generated = 0;
        $skipped   = 0;
        $failed    = 0;

        /** @var array<string, string> $claimedNamespaces Operation namespace => the URL that claimed it */
        $claimedNamespaces = [];

        foreach ($this->context[$version]->getDefinitions() as $url => $definition) {
            if (($definition['resourceType'] ?? null) !== 'OperationDefinition') {
                continue;
            }

            if (!$generator->canGenerate($definition)) {
                ++$skipped;
                $output->writeln("<comment>Skipping {$url} (kind=" . ($definition['kind'] ?? '?') . ')</comment>');

                continue;
            }

            // Contained per definition, like every sibling phase. The generator is deliberately
            // fatal on an unnamable stem, a colliding parameter name, an unresolvable polymorphic
            // type or an unnamable part path — but an escaping throw is not survivable here: Phase 2
            // has already deleted `Models/src/{$version}`, and Phase 6 (`outputGeneratedFiles`) is
            // what writes it back. Aborting between the two leaves the version's whole public
            // surface deleted until a clean regen succeeds.
            //
            // `addError` still trips `hasErrors()` regardless of the severity string (it appends to
            // the same list), so `execute()` returns Command::FAILURE and a broken definition can
            // never be silently absent from a green build. The severity is 'error' because that is
            // what it is; it drives `getErrorsBySeverity()` and the display, not the exit code.
            try {
                $holder = $generator->generate($definition, $version, $this->context[$version]);
            } catch (\Throwable $e) {
                ++$failed;
                $this->errorCollector->addError(
                    sprintf('Could not generate operation "%s": %s', $definition['code'] ?? $url, $e->getMessage()),
                    $url,
                    'OPERATION_GENERATION_ERROR',
                    'error',
                    ['exception_class' => get_class($e), 'fhir_version' => $version],
                );
                $output->writeln("<error>Failed to generate operation from {$url}: {$e->getMessage()}</error>");

                continue;
            }

            // The namespace comes off the class the generator built, not from one assembled here:
            // it nests per operation (`…\Operation\CodeSystemLookup`) to match the file layout, and
            // rebuilding it here would silently disagree with the generator and break PSR-4.
            $namespace = $holder->getNamespace()?->getName()
                ?? "Ardenexal\\FHIRTools\\Component\\Models\\{$version}\\Operation";

            // Two definitions can derive the same class stem — `classStem` is
            // pascal(resource[0]) . pascal(code), so an IG redefining `Patient/$match` produces the
            // same namespace, class names and file paths as the core definition. `addType` keys on
            // the URL so both would survive here, and `outputGeneratedFiles` would then write both to
            // the same path: the second silently overwrites the first while the count below counts
            // both. The result is a legal, invocable operation missing from the generated API with a
            // successful-looking build.
            //
            // `OperationClassNamer::assertNoCollisions` does not cover this — it checks parameter
            // names within one class, two levels below. Checked on the namespace rather than on a
            // recomputed stem because the namespace is what actually determines the file path.
            //
            // First claimant wins, so the outcome is deterministic regardless of definition order.
            if (isset($claimedNamespaces[$namespace])) {
                ++$failed;
                $this->errorCollector->addError(
                    sprintf(
                        'Operation "%s" derives the class namespace %s, already claimed by "%s". '
                        . 'Emitting it would overwrite the other operation\'s files.',
                        $url,
                        $namespace,
                        $claimedNamespaces[$namespace],
                    ),
                    $url,
                    'OPERATION_NAMESPACE_COLLISION',
                    'error',
                    ['fhir_version' => $version, 'namespace' => $namespace],
                );
                $output->writeln("<error>Namespace collision: {$url} would overwrite {$claimedNamespaces[$namespace]}</error>");

                continue;
            }

            $claimedNamespaces[$namespace] = $url;

            $this->context[$version]->addType($url . '#operation', $namespace, $holder);

            ++$generated;
        }

        // Reported rather than silent: "0 operations generated" and "47 operations generated" look
        // identical in a passing build otherwise, and the count is an M02 exit criterion. Failures
        // are counted separately so a partial run is legible at a glance rather than only in the
        // error list at the end.
        $output->writeln("<info>Generated {$generated} operation holders for {$version} ({$skipped} skipped, {$failed} failed).</info>");
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

                // The unsupported-kind guard above already returned every other kind to the loop,
                // so only the three routable kinds reach here and 'primitive-type' is the default.
                match ($kind) {
                    'resource'     => $resourceCount++,
                    'complex-type' => $dataTypeCount++,
                    default        => $primitiveCount++,
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
     * Generate PHP extension classes from base FHIR StructureDefinitions with derivation=constraint
     * and type=Extension, writing them to Models/src/{version}/Extension/.
     *
     * These classes represent named FHIR extensions from the core packages (e.g. patient-birthPlace,
     * us-core-race). Generating them here alongside the canonical base types means that
     * FHIRIGGeneratorCommand can reference them as pre-built parent classes rather than having to
     * reconstruct them from scratch at IG-generation time.
     *
     * Errors for individual definitions are non-fatal — a bad definition is logged and skipped.
     */
    private function buildExtensions(OutputInterface $output, string $version): void
    {
        $output->writeln('Generating base extension classes...');

        $baseNamespace  = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}";
        $extensionNs    = new PhpNamespace("{$baseNamespace}\\Extension");
        $generator      = new FHIRExtensionGenerator();
        $errorCollector = new ErrorCollector();
        $count          = 0;

        foreach ($this->context[$version]->getDefinitions() as $def) {
            if (($def['resourceType'] ?? '') !== 'StructureDefinition') {
                continue;
            }

            if (($def['derivation'] ?? '') !== 'constraint' || ($def['type'] ?? '') !== 'Extension') {
                continue;
            }

            $url = $def['url'] ?? '';
            // Skip if already registered from a prior run or dependency load
            if ($url === '' || $this->context[$version]->getType($url) !== null) {
                continue;
            }

            try {
                $class = $generator->generate($def, $version, $this->context[$version], $extensionNs, $errorCollector);
                $this->context[$version]->addType($url, $extensionNs->getName(), $class);

                $outputPath = Path::canonicalize(
                    __DIR__ . "/../../../Models/src/{$version}/Extension/{$class->getName()}.php",
                );
                $this->filesystem->mkdir(dirname($outputPath), 0755);
                $this->filesystem->dumpFile($outputPath, self::asPhpFile($class, $extensionNs->getName()));

                if ($output->isVerbose()) {
                    $output->writeln("  Extension: {$class->getName()}");
                }

                ++$count;
            } catch (\Throwable $e) {
                $name = $def['name'] ?? $url;
                $this->errorCollector->addError(
                    "Could not generate base extension '{$name}': {$e->getMessage()}",
                    $url,
                    'BASE_EXTENSION_GENERATION_ERROR',
                    'warning',
                );
                if ($output->isVerbose()) {
                    $output->writeln("<comment>  Skipped base extension '{$name}': {$e->getMessage()}</comment>");
                }
            }
        }

        $output->writeln("<info>Generated {$count} base extensions</info>");
    }

    /**
     * Generate PHP profile classes from base FHIR StructureDefinitions with derivation=constraint,
     * kind=resource|complex-type, and type≠Extension. Writes to Models/src/{version}/Profile/.
     *
     * These represent core FHIR constraint profiles (e.g. bp, vitalsigns, headcircum). Making them
     * part of the base model output allows FHIRIGGeneratorCommand to resolve their FQCNs for IG
     * profiles that extend them, without needing to regenerate them on every IG generation run.
     *
     * Errors for individual definitions are non-fatal — a bad definition is logged and skipped.
     */
    private function buildProfiles(OutputInterface $output, string $version): void
    {
        $output->writeln('Generating base profile classes...');

        $baseNamespace  = "Ardenexal\\FHIRTools\\Component\\Models\\{$version}";
        $profileNs      = new PhpNamespace("{$baseNamespace}\\Profile");
        $generator      = new FHIRProfileGenerator();
        $errorCollector = new ErrorCollector();
        $count          = 0;

        foreach ($this->context[$version]->getDefinitions() as $def) {
            if (($def['resourceType'] ?? '') !== 'StructureDefinition') {
                continue;
            }

            if (($def['derivation'] ?? '') !== 'constraint') {
                continue;
            }

            // Extensions are handled by buildExtensions(); logical models are skipped entirely
            if (($def['type'] ?? '') === 'Extension' || ($def['kind'] ?? '') === 'logical') {
                continue;
            }

            $kind = $def['kind'] ?? '';
            if (!in_array($kind, ['resource', 'complex-type'], true)) {
                continue;
            }

            $url = $def['url'] ?? '';
            // Skip if already registered (e.g. by a prior run or dependency load)
            if ($url === '' || $this->context[$version]->getType($url) !== null) {
                continue;
            }

            try {
                $class = $generator->generate($def, $version, $this->context[$version], $profileNs, $errorCollector);
                $this->context[$version]->addType($url, $profileNs->getName(), $class);

                $outputPath = Path::canonicalize(
                    __DIR__ . "/../../../Models/src/{$version}/Profile/{$class->getName()}.php",
                );
                $this->filesystem->mkdir(dirname($outputPath), 0755);
                $this->filesystem->dumpFile($outputPath, self::asPhpFile($class, $profileNs->getName()));

                if ($output->isVerbose()) {
                    $output->writeln("  Profile: {$class->getName()}");
                }

                ++$count;
            } catch (\Throwable $e) {
                $name = $def['name'] ?? $url;
                $this->errorCollector->addError(
                    "Could not generate base profile '{$name}': {$e->getMessage()}",
                    $url,
                    'BASE_PROFILE_GENERATION_ERROR',
                    'warning',
                );
                if ($output->isVerbose()) {
                    $output->writeln("<comment>  Skipped base profile '{$name}': {$e->getMessage()}</comment>");
                }
            }
        }

        $output->writeln("<info>Generated {$count} base profiles</info>");
    }

    /**
     * Generate PHP enums from FHIR ValueSets that were referenced during class building.     *
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

            if (str_contains($attributeName, 'FHIRExtensionDefinition')) {
                return new PhpNamespace("{$baseNamespace}\\Extension");
            }

            if (str_contains($attributeName, 'FHIRProfile')) {
                return new PhpNamespace("{$baseNamespace}\\Profile");
            }

            // Operation holders and payloads. Payload classes deliberately carry no FhirResource or
            // FhirProperty — that is what keeps them off the normalizers' supports*() path — so
            // without this branch they would fall through to the DataType default below and be
            // written to the wrong directory.
            if (str_contains($attributeName, 'FhirOperation')) {
                return new PhpNamespace("{$baseNamespace}\\Operation");
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
     * For example, PatientContact (a backbone element of Patient) goes to:
     *   Models/src/R4/Resource/Patient/PatientContact.php
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

        $typeName = $type->getName();

        // Operation classes nest under their operation, the way backbone elements nest under their
        // parent resource: CodeSystemLookupOutProperty goes to Operation/CodeSystemLookup/. One
        // operation emits up to six classes, so a flat directory would be unreadable at 154 of them.
        if ($typeName !== null && $type instanceof ClassType && $typeCategory === 'Operation') {
            $stem = $this->getOperationStem($type);

            if ($stem !== null) {
                return Path::canonicalize("{$basePath}/{$version}/Operation/{$stem}/{$typeName}.php");
            }
        }

        // If this is a backbone element in the Resource category, nest it under its parent resource
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
    /**
     * The operation class stem a generated operation class belongs to, e.g. `CodeSystemLookup`.
     *
     * Payloads carry it on `FhirOperationPayload::$operation`; holders are named `{Stem}Operation`,
     * so the stem is recoverable from the class name. Reading it from the attribute where one exists
     * rather than parsing every name keeps payload placement independent of the naming convention.
     */
    private function getOperationStem(ClassType $type): ?string
    {
        foreach ($type->getAttributes() as $attribute) {
            $name = $attribute->getName();

            if (str_contains($name, 'FhirOperationPayload')) {
                $operation = $attribute->getArguments()['operation'] ?? null;

                return is_string($operation) && $operation !== '' ? $operation : null;
            }

            if (str_contains($name, 'FhirOperation') && !str_contains($name, 'Parameter')) {
                $className = $type->getName() ?? '';

                return str_ends_with($className, 'Operation')
                    ? substr($className, 0, -strlen('Operation'))
                    : null;
            }
        }

        return null;
    }

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

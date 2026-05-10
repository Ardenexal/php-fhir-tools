<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Command;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRConstrainedComplexTypeGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRExtensionGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRProfileGenerator;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageLoader;
use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageMetadata;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;

use function Symfony\Component\String\u;

/**
 * Console command that generates typed PHP classes for FHIR Implementation Guide extensions
 * and profiles.
 *
 * Unlike {@see FHIRModelGeneratorCommand} (which generates the canonical base FHIR types),
 * this command targets the IG-specific layer:
 *
 *   - **Named extensions** — StructureDefinitions with type=Extension, derivation=constraint.
 *     Each becomes a typed subclass of the base Extension class with the URL baked in and
 *     value/sub-extension properties narrowed to the concrete types defined by the IG.
 *
 *   - **Resource/type profiles** — StructureDefinitions with derivation=constraint and kind
 *     resource or complex-type. Each becomes a thin subclass of its base resource/type,
 *     carrying a PROFILE_URL constant and a #[FHIRProfile] attribute.
 *
 * The output is written to a separate namespace tree that never touches the canonical base
 * models, so both can evolve independently:
 *
 *   Models/src/
 *   ├── R4/               ← canonical base types (fhir:generate)
 *   └── IG/
 *       └── R4/
 *           ├── AuBase/
 *           │   ├── Extension/   ← AU Base extensions
 *           │   └── Profile/     ← AU Base profiles (e.g. AUBasePatientProfile)
 *           └── AuCore/
 *               ├── Extension/   ← AU Core extensions
 *               └── Profile/     ← AU Core profiles (e.g. AUCorePatientProfile extends AUBasePatientProfile)
 *
 * **Multi-level profile chains** (e.g. AU Core → AU Base → FHIR R4) are supported by
 * specifying packages in dependency order. Each package's generated classes are registered
 * in the BuilderContext so subsequent packages can extend them.
 *
 * Usage:
 *   # Single IG (extensions + profiles for AU Base):
 *   php bin/console fhir:generate-ig --package=hl7.fhir.au.base
 *
 *   # Chained IGs (AU Core extends AU Base):
 *   php bin/console fhir:generate-ig --package=hl7.fhir.au.base --package=hl7.fhir.au.core
 *
 * @see FHIRExtensionGenerator
 * @see FHIRProfileGenerator
 * @see FHIRModelGeneratorCommand
 */
#[AsCommand(name: 'fhir:generate-ig', description: 'Generates typed PHP classes for IG extensions and profiles.')]
class FHIRIGGeneratorCommand extends Command
{
    /**
     * Base FHIR packages that must be loaded for type resolution, indexed by FHIR version.
     * These are never written to disk — they only populate the BuilderContext types.
     *
     * @var array<string, list<string>>
     */
    private const array BASE_PACKAGES = [
        'R4'  => ['hl7.terminology.r4#7.0.0', 'hl7.fhir.r4.core#4.0.1', 'hl7.fhir.uv.extensions.r4#5.2.0'],
        'R4B' => ['hl7.terminology.r4b#6.0.2', 'hl7.fhir.r4b.core#4.3.0', 'hl7.fhir.uv.extensions.r4b#5.2.0'],
        'R5'  => ['hl7.terminology.r5#7.0.0', 'hl7.fhir.r5.core#5.0.0', 'hl7.fhir.uv.extensions.r5#5.2.0'],
    ];

    /**
     * Default namespace root for generated IG classes (library-internal fallback).
     * Override via the fhir.ig.namespace bundle configuration key.
     */
    private const string IG_BASE_NAMESPACE = 'Ardenexal\\FHIRTools\\Component\\Models\\IG';

    /**
     * Base namespace root for canonical FHIR model classes (used to register base types).
     */
    private const string MODELS_BASE_NAMESPACE = 'Ardenexal\\FHIRTools\\Component\\Models';

    private Filesystem $filesystem;

    private PackageLoader $packageLoader;

    private ErrorCollector $errorCollector;

    /** Effective base namespace for generated IG classes, resolved from bundle config or the default. */
    private string $igBaseNamespace;

    /** Effective output directory base, resolved from bundle config or computed from __DIR__. */
    private ?string $igOutputDirectory;

    /**
     * IG packages declared in bundle config (fhir.ig.packages), used as a default when
     * no --package CLI arguments are supplied at runtime.
     *
     * @var list<string>
     */
    private array $configuredPackages;

    /** Default value for --offline, driven by fhir.ig.offline in bundle config. */
    private bool $offlineByDefault;

    /**
     * One BuilderContext per FHIR version, shared across all processed IG packages.
     * Base types and IG types are all registered here so later packages can extend earlier ones.
     *
     * @var array<string, BuilderContext>
     */
    private array $context;

    /**
     * @param array<string> $configuredPackages IG packages from fhir.ig.packages bundle config
     * @param bool          $offlineByDefault   fhir.ig.offline bundle config value
     * @param string|null   $igOutputDirectory  fhir.ig.output_directory bundle config value
     * @param string|null   $igBaseNamespace    fhir.ig.namespace bundle config value
     */
    public function __construct(
        Filesystem $filesystem,
        PackageLoader $packageLoader,
        array $configuredPackages = [],
        bool $offlineByDefault = false,
        ?string $igOutputDirectory = null,
        ?string $igBaseNamespace = null,
    ) {
        parent::__construct();
        $this->filesystem         = $filesystem;
        $this->packageLoader      = $packageLoader;
        $this->configuredPackages = array_values($configuredPackages);
        $this->offlineByDefault   = $offlineByDefault;
        $this->igOutputDirectory  = ($igOutputDirectory !== '' && $igOutputDirectory !== null)
            ? $igOutputDirectory
            : null;
        $this->igBaseNamespace   = ($igBaseNamespace !== '' && $igBaseNamespace !== null)
            ? $igBaseNamespace
            : self::IG_BASE_NAMESPACE;
        $this->errorCollector    = new ErrorCollector();
        $this->context           = [
            'R4'  => new BuilderContext(),
            'R4B' => new BuilderContext(),
            'R5'  => new BuilderContext(),
        ];
    }

    /**
     * Entry point for `fhir:generate-ig`.
     *
     * When --package is supplied, those packages are used exclusively.
     * When --package is omitted, the packages configured under fhir.ig.packages in the
     * bundle configuration are used, enabling a no-argument workflow for end-user projects.
     *
     * @param OutputInterface $output      Console output
     * @param array<string>   $packages    IG packages to generate, e.g. ['hl7.fhir.au.base#1.0.0']
     * @param bool            $offlineMode Use cached packages only — no network
     */
    public function __invoke(
        OutputInterface $output,
        #[Option(description: 'IG packages to generate (specify in dependency order). Overrides fhir.ig.packages config when supplied.', name: 'package')]
        array $packages = [],
        #[Option(description: 'Work offline using only cached packages. Defaults to fhir.ig.offline config when not passed.', name: 'offline')]
        bool $offlineMode = false,
    ): int {
        // Resolve effective package list: CLI args take priority, config is the fallback
        $effectivePackages = !empty($packages) ? $packages : $this->configuredPackages;
        $effectiveOffline  = $offlineMode || $this->offlineByDefault;

        if (empty($effectivePackages)) {
            $output->writeln(
                '<error>No packages specified. Use --package=hl7.fhir.au.base or configure fhir.ig.packages in your bundle configuration.</error>',
            );

            return Command::FAILURE;
        }

        try {
            $this->errorCollector->clear();

            return $this->executeGeneration($output, $effectivePackages, $effectiveOffline);
        } catch (\Throwable $e) {
            $output->writeln("<error>Fatal error: {$e->getMessage()}</error>");

            if ($output->isVerbose()) {
                $output->writeln($e->getTraceAsString());
            }

            return Command::FAILURE;
        }
    }

    /**
     * Main generation pipeline.
     *
     * @param array<string> $igPackages User-specified IG packages in dependency order
     */
    private function executeGeneration(OutputInterface $output, array $igPackages, bool $offlineMode): int
    {
        $output->writeln('<info>Generating IG extension and profile classes...</info>');

        // Phase 1 — Install all IG packages and determine which FHIR versions are needed
        $indicator = new ProgressIndicator($output);
        $indicator->start('Installing IG packages...');

        /** @var list<array{metadata: PackageMetadata, definitions: array<string, array<string, mixed>>}> $igData */
        $igData           = [];
        $affectedVersions = [];

        foreach ($igPackages as $packageSpec) {
            [$packageName, $packageVersion] = $this->parsePackageSpec($packageSpec);
            $indicator->setMessage("Installing {$packageName}...");

            try {
                $metadata    = $this->packageLoader->installPackage(
                    packageName: $packageName,
                    version: $packageVersion,
                    registry: null,
                    resolveDeps: false,
                    offlineMode: $offlineMode,
                );
                $definitions = $this->packageLoader->loadPackageStructureDefinitions($metadata);
                $igData[]    = ['metadata' => $metadata, 'definitions' => $definitions];

                foreach ($metadata->getFhirVersions() as $v) {
                    if (!in_array($v, $affectedVersions, true)) {
                        $affectedVersions[] = $v;
                    }
                }
            } catch (\Throwable $e) {
                $output->writeln("<error>Failed to install {$packageName}: {$e->getMessage()}</error>");
                $this->errorCollector->addError($e->getMessage(), $packageName, 'PACKAGE_INSTALL_ERROR');
            }

            $indicator->advance();
        }

        $indicator->finish('IG packages installed.');

        // Phase 2 — For each FHIR version, load base types into context and generate
        foreach ($affectedVersions as $fhirVersion) {
            $supported = match ($fhirVersion) {
                'R4', 'R4B', 'R5'  => true,
                default            => false,
            };

            if (!$supported) {
                $output->writeln("<comment>Skipping unsupported FHIR version: {$fhirVersion}</comment>");

                continue;
            }

            $output->writeln("\n<info>Processing FHIR version: {$fhirVersion}</info>");

            // Register base FHIR namespaces so FHIRModelGenerator can route types correctly
            $this->setupBaseNamespaces($fhirVersion);

            // Load base FHIR packages and generate their types in-memory for context population
            $this->loadBasePackages($output, $fhirVersion, $offlineMode);
            $this->generateBaseTypesInMemory($output, $fhirVersion);

            // Register base FHIR core extensions (derivation=constraint, type=Extension) in
            // context so that IG extension generators can resolve them as parent class FQCNs.
            // Files are only written when they do not already exist (they may have been produced
            // by a prior `fhir:generate` run).
            $this->generateBaseExtensionsInMemory($output, $fhirVersion);

            // Generate + write base FHIR core profiles (derivation=constraint) and register
            // them in context. This ensures that IG profiles which extend a core constraint
            // profile (e.g. AU Core blood pressure → hl7.fhir.r4.core bp profile →
            // Observation) can resolve the parent FQCN instead of falling back to a heuristic.
            // Files are only written when they do not already exist (they may have been produced
            // by a prior `fhir:generate` run).
            $this->generateBaseProfilesInMemory($output, $fhirVersion);

            // Load the full transitive dependency tree of all IG packages into context.
            // Dependency packages are only used for type resolution — no PHP files are written
            // for them. Pre-mark the explicitly listed IG packages as already handled so they
            // are not re-loaded as deps (generateIGPackage handles them with file writing).
            //
            // NOTE: if an IG package A depends on another IG package B that is also in the
            // user's --package list, A's dep-loader skips B (pre-marked). This means A relies
            // on generateIGPackage(B) having already registered B's types before A is
            // generated. This works correctly as long as packages are listed in dependency
            // order (B before A). Listing them out of order will produce fallback FQCNs with
            // a warning rather than a hard error.
            $depLoaded = [];
            foreach ($igData as $ig) {
                $depLoaded[$ig['metadata']->getName()] = true;
            }

            foreach ($igData as $ig) {
                if (!in_array($fhirVersion, $ig['metadata']->getFhirVersions(), true)) {
                    continue;
                }

                $this->loadDependencyPackagesIntoContext($output, $ig['metadata'], $fhirVersion, $offlineMode, $depLoaded);
            }

            // Process each IG package in the order specified (dependency order)
            foreach ($igData as $ig) {
                $versionMatch = in_array($fhirVersion, $ig['metadata']->getFhirVersions(), true);
                if (!$versionMatch) {
                    continue;
                }

                $slug = $this->derivePackageSlug($ig['metadata']->getName());
                $output->writeln("  Generating IG classes for <comment>{$ig['metadata']->getName()}</comment> → <comment>{$slug}</comment>");

                $this->generateIGPackage(
                    $output,
                    $fhirVersion,
                    $slug,
                    $ig['definitions'],
                );
            }
        }

        if ($this->errorCollector->hasErrors()) {
            $output->writeln('<error>Generation completed with errors:</error>');
            $output->writeln($this->errorCollector->getDetailedOutput());

            return Command::FAILURE;
        }

        if ($this->errorCollector->hasWarnings()) {
            $output->writeln('<comment>Generation warnings:</comment>');
            foreach ($this->errorCollector->getWarnings() as $warning) {
                $output->writeln("  <comment>[WARN] {$warning['message']}</comment>");
            }
        }

        $output->writeln('<info>IG class generation completed successfully!</info>');

        return Command::SUCCESS;
    }

    /**
     * Register the standard base FHIR namespaces in the per-version BuilderContext.
     *
     * FHIRModelGenerator consults these namespaces when placing types into the context.
     */
    private function setupBaseNamespaces(string $version): void
    {
        $base = self::MODELS_BASE_NAMESPACE . "\\{$version}";

        $this->context[$version]->addElementNamespace($version, new PhpNamespace("{$base}\\Resource"));
        $this->context[$version]->addDatatypeNamespace($version, new PhpNamespace("{$base}\\DataType"));
        $this->context[$version]->addPrimitiveNamespace($version, new PhpNamespace("{$base}\\Primitive"));
        $this->context[$version]->addEnumNamespace($version, new PhpNamespace("{$base}\\Enum"));
    }

    /**
     * Download (or load from cache) the base FHIR core packages for a given version and
     * merge their definitions into the per-version BuilderContext.
     */
    private function loadBasePackages(OutputInterface $output, string $version, bool $offlineMode): void
    {
        $output->writeln("  Loading base {$version} packages for type resolution...");

        foreach (self::BASE_PACKAGES[$version] as $packageSpec) {
            [$name, $ver] = $this->parsePackageSpec($packageSpec);

            try {
                $metadata    = $this->packageLoader->installPackage(
                    packageName: $name,
                    version: $ver,
                    registry: null,
                    resolveDeps: false,
                    offlineMode: $offlineMode,
                );
                $definitions = $this->packageLoader->loadPackageStructureDefinitions($metadata);
                $this->context[$version]->loadDefinitions($definitions);
                $output->writeln("    Loaded <comment>{$name}</comment>");
            } catch (\Throwable $e) {
                $output->writeln("<comment>  Warning: could not load base package {$name}: {$e->getMessage()}</comment>");
            }
        }
    }

    /**
     * Run FHIRModelGenerator over all base (non-constraint, non-logical) StructureDefinitions
     * already loaded into the context, populating the type registry without writing any files.
     *
     * This is required so that FHIRExtensionGenerator and FHIRProfileGenerator can resolve
     * parent classes and value types via BuilderContext::getType().
     */
    private function generateBaseTypesInMemory(OutputInterface $output, string $version): void
    {
        $output->writeln("  Registering base {$version} types in memory...");

        $generator      = new FHIRModelGenerator();
        $errorCollector = new ErrorCollector();

        $resourceNs  = $this->context[$version]->getElementNamespace($version);
        $datatypeNs  = $this->context[$version]->getDatatypeNamespace($version);
        $primitiveNs = $this->context[$version]->getPrimitiveNamespace($version);

        $count = 0;
        foreach ($this->context[$version]->getDefinitions() as $def) {
            if (($def['resourceType'] ?? '') !== 'StructureDefinition') {
                continue;
            }

            if (($def['kind'] ?? '') === 'logical' || ($def['derivation'] ?? '') === 'constraint') {
                continue;
            }

            $kind     = $def['kind'] ?? '';
            $targetNs = match ($kind) {
                'resource'       => $resourceNs,
                'complex-type'   => $datatypeNs,
                'primitive-type' => $primitiveNs,
                default          => null,
            };

            if ($targetNs === null) {
                continue;
            }

            $class = $generator->generateModelClassWithErrorHandling(
                $def,
                $version,
                $errorCollector,
                $this->context[$version],
            );

            if ($class !== null) {
                $url = $def['url'] ?? '';
                // Only register if not already in context (avoids duplicate-add errors)
                if ($url !== '' && $this->context[$version]->getType($url) === null) {
                    $this->context[$version]->addType($url, $targetNs->getName(), $class);
                }

                ++$count;
            }
        }

        $output->writeln("    Registered <comment>{$count}</comment> base types.");
    }

    /**
     * Generate PHP extension classes for all named extensions (derivation=constraint, type=Extension)
     * from already-loaded base packages, register them in the BuilderContext, and write them to
     * Models/src/{version}/Extension/ if not already present on disk.
     *
     * This step is required so that IG extensions whose baseDefinition points to a FHIR core
     * extension can resolve the parent FQCN via context lookup. Writing is skipped when the file
     * already exists (e.g. produced by a prior `fhir:generate` run) to avoid redundant work.
     */
    private function generateBaseExtensionsInMemory(OutputInterface $output, string $version): void
    {
        $output->writeln("  Registering base {$version} extensions...");

        $extensionNs        = new PhpNamespace(self::MODELS_BASE_NAMESPACE . "\\{$version}\\Extension");
        $extensionGenerator = new FHIRExtensionGenerator();
        $errorCollector     = new ErrorCollector();

        $count = 0;
        foreach ($this->context[$version]->getDefinitions() as $def) {
            if (($def['resourceType'] ?? '') !== 'StructureDefinition') {
                continue;
            }

            if (($def['derivation'] ?? '') !== 'constraint' || ($def['type'] ?? '') !== 'Extension') {
                continue;
            }

            $url = $def['url'] ?? '';
            // Skip if already registered (e.g. by a prior run or dependency load)
            if ($url === '' || $this->context[$version]->getType($url) !== null) {
                continue;
            }

            try {
                $class = $extensionGenerator->generate($def, $version, $this->context[$version], $extensionNs, $errorCollector);
                $this->context[$version]->addType($url, $extensionNs->getName(), $class);
                $this->writeBaseExtensionClass($output, $class, $extensionNs, $version);
                ++$count;
            } catch (\Throwable $e) {
                // Non-fatal: warn and continue so that one bad definition does not abort the run
                $output->writeln(
                    "<comment>  Warning: could not generate base extension for '{$url}': {$e->getMessage()}</comment>",
                );
            }
        }

        $output->writeln("    Registered <comment>{$count}</comment> base extensions.");
    }

    /**
     * Generate PHP profile classes for all constraint StructureDefinitions from already-loaded
     * base packages, write them to disk, and register them in the BuilderContext.
     *
     * This step is required so that IG profiles whose baseDefinition points to a FHIR core
     * constraint profile (e.g. http://hl7.org/fhir/StructureDefinition/bp → vitalsigns → Observation)
     * can resolve the parent FQCN via context lookup instead of falling back to a heuristic class
     * name that does not exist.
     *
     * Generated files are written to Models/src/{version}/Profile/ alongside the canonical
     * base FHIR types. Writing is skipped when the file already exists (e.g. produced by a
     * prior `fhir:generate` run) to avoid redundant work.
     */
    private function generateBaseProfilesInMemory(OutputInterface $output, string $version): void
    {
        $output->writeln("  Registering base {$version} constraint profiles...");

        $profileNs        = new PhpNamespace(self::MODELS_BASE_NAMESPACE . "\\{$version}\\Profile");
        $profileGenerator = new FHIRProfileGenerator();
        $errorCollector   = new ErrorCollector();

        $count = 0;
        foreach ($this->context[$version]->getDefinitions() as $def) {
            if (($def['resourceType'] ?? '') !== 'StructureDefinition') {
                continue;
            }

            if (($def['derivation'] ?? '') !== 'constraint') {
                continue;
            }

            if (($def['kind'] ?? '') === 'logical' || ($def['type'] ?? '') === 'Extension') {
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
                $class = $profileGenerator->generate($def, $version, $this->context[$version], $profileNs, $errorCollector);
                $this->context[$version]->addType($url, $profileNs->getName(), $class);
                $this->writeBaseProfileClass($output, $class, $profileNs, $version);
                ++$count;
            } catch (\Throwable $e) {
                // Non-fatal: warn and continue so that one bad definition does not abort the run
                $output->writeln(
                    "<comment>  Warning: could not generate base profile for '{$url}': {$e->getMessage()}</comment>",
                );
            }
        }

        $output->writeln("    Registered <comment>{$count}</comment> base constraint profiles.");
    }

    /**
     * Write a base FHIR core extension class to Models/src/{version}/Extension/.
     * Skips the write if the file already exists (e.g. written by a prior `fhir:generate` run).
     */
    private function writeBaseExtensionClass(
        OutputInterface $output,
        ClassType $class,
        PhpNamespace $namespace,
        string $version,
    ): void {
        $outputPath = Path::canonicalize(
            $this->resolveBaseModelsPath() . "/{$version}/Extension/{$class->getName()}.php",
        );

        if ($this->filesystem->exists($outputPath)) {
            if ($output->isVerbose()) {
                $output->writeln("    Skipped (already exists): {$outputPath}");
            }

            return;
        }

        $directory = dirname($outputPath);
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->mkdir($directory, 0755);
        }

        $contents = $this->renderPhpFile($class, $namespace);
        $this->filesystem->dumpFile($outputPath, $contents);

        if ($output->isVerbose()) {
            $output->writeln("    Written base extension: {$outputPath}");
        }
    }

    /**
     * Write a base FHIR core profile class to Models/src/{version}/Profile/.
     * Skips the write if the file already exists (e.g. written by a prior `fhir:generate` run).
     */
    private function writeBaseProfileClass(
        OutputInterface $output,
        ClassType $class,
        PhpNamespace $namespace,
        string $version,
    ): void {
        $outputPath = Path::canonicalize(
            $this->resolveBaseModelsPath() . "/{$version}/Profile/{$class->getName()}.php",
        );

        if ($this->filesystem->exists($outputPath)) {
            if ($output->isVerbose()) {
                $output->writeln("    Skipped (already exists): {$outputPath}");
            }

            return;
        }

        $directory = dirname($outputPath);
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->mkdir($directory, 0755);
        }

        $contents = $this->renderPhpFile($class, $namespace);
        $this->filesystem->dumpFile($outputPath, $contents);

        if ($output->isVerbose()) {
            $output->writeln("    Written base profile: {$outputPath}");
        }
    }

    /**
     * Resolve the absolute path to the canonical Models/src directory.
     *
     * Used to write base FHIR core profile classes alongside the generated model types.
     */
    private function resolveBaseModelsPath(): string
    {
        return Path::canonicalize(__DIR__ . '/../../../Models/src');
    }

    /**
     * Generate extension and profile classes for one IG package and write them to disk.
     *
     * Constraint derivations are routed:
     *   - type=Extension                                       → FHIRExtensionGenerator             → Extension/
     *   - kind=complex-type with fixed[x]/pattern[x] elements → FHIRConstrainedComplexTypeGenerator → Profile/
     *   - other kinds                                         → FHIRProfileGenerator                → Profile/
     *
     * Generated types are also registered in the BuilderContext so that subsequent IG
     * packages (processed later in the loop) can resolve them as parent classes.
     *
     * @param array<string, array<string, mixed>> $definitions Definitions from the IG package
     */
    private function generateIGPackage(
        OutputInterface $output,
        string $version,
        string $slug,
        array $definitions,
    ): void {
        $baseNs          = $this->igBaseNamespace . "\\{$version}\\{$slug}";
        $extensionNs     = new PhpNamespace("{$baseNs}\\Extension");
        $profileNs       = new PhpNamespace("{$baseNs}\\Profile");

        $extensionGenerator              = new FHIRExtensionGenerator();
        $profileGenerator                = new FHIRProfileGenerator();
        $constrainedComplexTypeGenerator = new FHIRConstrainedComplexTypeGenerator();

        /** @var list<array{class: ClassType, namespace: PhpNamespace, category: string}> $generated */
        $generated = [];

        // Load IG definitions into context so extension/profile generators can look up
        // types that appear within the same package (e.g. an extension's value type is
        // another type defined in the same IG).
        $this->context[$version]->loadDefinitions($definitions);

        foreach ($definitions as $url => $def) {
            if (($def['resourceType'] ?? '') !== 'StructureDefinition') {
                continue;
            }

            if (($def['derivation'] ?? '') !== 'constraint') {
                continue;
            }

            if (($def['kind'] ?? '') === 'logical') {
                continue;
            }

            $name = $def['name'] ?? 'Unknown';
            $type = $def['type'] ?? '';
            $kind = $def['kind'] ?? '';

            try {
                if ($type === 'Extension') {
                    $class = $extensionGenerator->generate($def, $version, $this->context[$version], $extensionNs, $this->errorCollector);
                    $this->context[$version]->addType($url, $extensionNs->getName(), $class);
                    $generated[] = ['class' => $class, 'namespace' => $extensionNs, 'category' => 'Extension'];
                    $output->writeln("    Extension: <comment>{$class->getName()}</comment>");
                } elseif ($kind === 'complex-type' && FHIRConstrainedComplexTypeGenerator::hasConstrainedElements($def)) {
                    $class = $constrainedComplexTypeGenerator->generate($def, $version, $this->context[$version], $profileNs, $this->errorCollector);
                    $this->context[$version]->addType($url, $profileNs->getName(), $class);
                    $generated[] = ['class' => $class, 'namespace' => $profileNs, 'category' => 'Profile'];
                    $output->writeln("    Profile:   <comment>{$class->getName()}</comment> (constrained)");
                } elseif (in_array($kind, ['resource', 'complex-type'], true)) {
                    $class = $profileGenerator->generate($def, $version, $this->context[$version], $profileNs, $this->errorCollector);
                    $this->context[$version]->addType($url, $profileNs->getName(), $class);
                    $generated[] = ['class' => $class, 'namespace' => $profileNs, 'category' => 'Profile'];
                    $output->writeln("    Profile:   <comment>{$class->getName()}</comment>");
                }
            } catch (\Throwable $e) {
                $output->writeln("<error>    Failed to generate {$name}: {$e->getMessage()}</error>");
                $this->errorCollector->addError($e->getMessage(), $url, 'IG_GENERATION_ERROR');
            }
        }

        // Write all generated classes to disk
        foreach ($generated as $item) {
            $this->writeClass($output, $item['class'], $item['namespace'], $version, $slug, $item['category']);
        }

        $extCount     = count(array_filter($generated, fn ($g) => $g['category'] === 'Extension'));
        $profileCount = count(array_filter($generated, fn ($g) => $g['category'] === 'Profile'));
        $output->writeln(
            "  <info>Generated {$extCount} extension(s) and {$profileCount} profile(s) for {$slug}.</info>",
        );
    }

    /**
     * Resolve the base output path for IG classes.
     *
     * When fhir.ig.output_directory is configured, that path is used directly.
     * Otherwise the library's own Models/src/IG directory is used as the default,
     * preserving backward compatibility for internal code generation.
     */
    private function resolveIGBasePath(): string
    {
        if ($this->igOutputDirectory !== null) {
            return Path::canonicalize($this->igOutputDirectory);
        }

        // Library-internal default: src/Component/Models/src/IG
        return Path::canonicalize(__DIR__ . '/../../../Models/src/IG');
    }

    /**
     * Write a generated class to disk under {igOutputDirectory}/{version}/{slug}/{category}/.
     */
    private function writeClass(
        OutputInterface $output,
        ClassType $class,
        PhpNamespace $namespace,
        string $version,
        string $slug,
        string $category,
    ): void {
        $outputPath = Path::canonicalize(
            $this->resolveIGBasePath() . "/{$version}/{$slug}/{$category}/{$class->getName()}.php",
        );

        $directory = dirname($outputPath);
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->mkdir($directory, 0755);
        }

        $contents = $this->renderPhpFile($class, $namespace);
        $this->filesystem->dumpFile($outputPath, $contents);

        if ($output->isVerbose()) {
            $output->writeln("    Written: {$outputPath}");
        }
    }

    /**
     * Render a ClassType to a PHP file string with strict_types, namespace declaration, and use imports.
     *
     * Uses two Nette Printer calls:
     *   1. printNamespace() — emits the namespace declaration and all accumulated `use` imports.
     *      Because ClassType objects are never add()ed to the PhpNamespace (the constructor only
     *      stores a back-reference), getClasses() returns empty and no class body is printed here.
     *   2. printClass() — emits the class body with FQCNs resolved to short names via the namespace.
     *
     * Combining the two produces a complete, valid PHP file.
     */
    private function renderPhpFile(ClassType $class, PhpNamespace $namespace): string
    {
        $printer = new Printer();

        // Namespace declaration + use imports (no class body — see docblock above)
        $header = $printer->printNamespace($namespace);
        // Class body with short-name resolution from the namespace context
        $classBody = $printer->printClass($class, $namespace);

        return "<?php declare(strict_types=1);\n\n" . $header . $classBody;
    }

    /**
     * Parse a package spec like "hl7.fhir.au.base#1.0.0" into [name, version|null].
     *
     * @return array{0: string, 1: string|null}
     */
    private function parsePackageSpec(string $spec): array
    {
        $parts = explode('#', $spec, 2);

        return [$parts[0], $parts[1] ?? null];
    }

    /**
     * Derive a PascalCase package slug from a FHIR package name.
     *
     * The slug is used as the subdirectory / namespace segment under IG/{version}/.
     *
     * Examples:
     *   hl7.fhir.au.base  → AuBase
     *   hl7.fhir.au.core  → AuCore
     *   hl7.fhir.us.core  → UsCore
     *   my.custom.ig      → MyCustomIg
     */
    private function derivePackageSlug(string $packageName): string
    {
        $parts = explode('.', $packageName);

        // Strip well-known vendor prefixes to produce compact slug names
        $skip = ['hl7', 'fhir'];

        $meaningful = array_filter($parts, static fn (string $p): bool => !in_array($p, $skip, true));

        if (empty($meaningful)) {
            $meaningful = $parts;
        }

        return implode('', array_map(
            static fn (string $p): string => u($p)->title(allWords: false)->toString(),
            array_values($meaningful),
        ));
    }

    /**
     * Recursively install and load the full transitive dependency tree of an IG package into
     * the per-version BuilderContext for type resolution.
     *
     * Dependencies are loaded into context (definitions + in-memory type registry) but no PHP
     * files are written to disk. This allows extension/profile generators to resolve
     * cross-package type references via BuilderContext::getType() during IG class generation.
     *
     * Packages already covered by BASE_PACKAGES (core FHIR types, terminology) are skipped
     * since they are loaded separately by loadBasePackages(). The $loaded set prevents
     * re-processing the same package across multiple recursion branches.
     *
     * @param array<string, bool> $loaded Visited package names — mutated in place to track progress
     */
    private function loadDependencyPackagesIntoContext(
        OutputInterface $output,
        PackageMetadata $metadata,
        string $version,
        bool $offlineMode,
        array &$loaded,
    ): void {
        $basePackageNames = array_map(
            static fn (string $spec): string => explode('#', $spec, 2)[0],
            self::BASE_PACKAGES[$version] ?? [],
        );

        foreach ($metadata->getDependencies() as $depName => $depConstraint) {
            if (isset($loaded[$depName])) {
                continue;
            }

            if (in_array($depName, $basePackageNames, true)) {
                continue;
            }

            $loaded[$depName] = true;

            try {
                $depMetadata = $this->packageLoader->installPackage(
                    packageName: $depName,
                    version: $depConstraint !== '' ? $depConstraint : null,
                    registry: null,
                    resolveDeps: false,
                    offlineMode: $offlineMode,
                );
                $definitions = $this->packageLoader->loadPackageStructureDefinitions($depMetadata);

                // Merge dep definitions into context so type lookups can find them
                $this->context[$version]->loadDefinitions($definitions);

                // Recurse FIRST (depth-first post-order) so that this dep's own transitive
                // dependencies are already in context before we generate this dep's classes.
                // Without this ordering, a package B whose StructureDefinitions reference
                // types from package C would produce fallback FQCNs for C's types.
                $this->loadDependencyPackagesIntoContext($output, $depMetadata, $version, $offlineMode, $loaded);

                // Now all transitive types are registered; generate this dep's classes.
                $this->registerDepTypesInContext($version, $definitions, $depName);

                if ($output->isVerbose()) {
                    $output->writeln("    Loaded dependency <comment>{$depName}</comment> into context");
                }
            } catch (\Throwable $e) {
                $output->writeln(
                    "<comment>  Warning: could not load dependency {$depName}: {$e->getMessage()}</comment>",
                );
            }
        }
    }

    /**
     * Generate in-memory extension and profile classes for a dependency package and register
     * them in the per-version BuilderContext.
     *
     * No PHP files are written to disk. The registered class info (FQCN + namespace) mirrors
     * what would be written if the user ran `fhir:generate-ig --package={depName}` — so any
     * IG that references these types will emit the correct PHP class names.
     *
     * @param array<string, array<string, mixed>> $definitions Definitions from the dep package
     */
    private function registerDepTypesInContext(string $version, array $definitions, string $depName): void
    {
        $slug        = $this->derivePackageSlug($depName);
        $baseNs      = $this->igBaseNamespace . "\\{$version}\\{$slug}";
        $extensionNs = new PhpNamespace("{$baseNs}\\Extension");
        $profileNs   = new PhpNamespace("{$baseNs}\\Profile");

        $extensionGenerator              = new FHIRExtensionGenerator();
        $profileGenerator                = new FHIRProfileGenerator();
        $constrainedComplexTypeGenerator = new FHIRConstrainedComplexTypeGenerator();

        foreach ($definitions as $url => $def) {
            if (($def['resourceType'] ?? '') !== 'StructureDefinition') {
                continue;
            }

            if (($def['derivation'] ?? '') !== 'constraint' || ($def['kind'] ?? '') === 'logical') {
                continue;
            }

            // Skip if already registered (e.g. from an earlier dep in the same tree)
            if ($this->context[$version]->getType($url) !== null) {
                continue;
            }

            try {
                $type = $def['type'] ?? '';
                $kind = $def['kind'] ?? '';

                if ($type === 'Extension') {
                    $class = $extensionGenerator->generate($def, $version, $this->context[$version], $extensionNs, $this->errorCollector);
                    $this->context[$version]->addType($url, $extensionNs->getName(), $class);
                } elseif ($kind === 'complex-type' && FHIRConstrainedComplexTypeGenerator::hasConstrainedElements($def)) {
                    $class = $constrainedComplexTypeGenerator->generate($def, $version, $this->context[$version], $profileNs, $this->errorCollector);
                    $this->context[$version]->addType($url, $profileNs->getName(), $class);
                } elseif (in_array($kind, ['resource', 'complex-type'], true)) {
                    $class = $profileGenerator->generate($def, $version, $this->context[$version], $profileNs, $this->errorCollector);
                    $this->context[$version]->addType($url, $profileNs->getName(), $class);
                }
            } catch (\Throwable $e) {
                $this->errorCollector->addWarning(
                    "Could not register dependency type '{$url}' from '{$depName}': {$e->getMessage()}",
                    $url,
                );
            }
        }
    }
}

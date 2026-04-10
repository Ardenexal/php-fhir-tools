<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Command;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
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
        'R4'  => ['hl7.terminology.r4#7.0.0', 'hl7.fhir.r4.core#4.0.1'],
        'R4B' => ['hl7.terminology.r4b#7.0.0', 'hl7.fhir.r4b.core#4.3.0'],
        'R5'  => ['hl7.terminology.r5#7.0.0', 'hl7.fhir.r5.core#5.0.0'],
    ];

    /**
     * Base namespace root for generated IG classes.
     */
    private const string IG_BASE_NAMESPACE = 'Ardenexal\\FHIRTools\\Component\\Models\\IG';

    /**
     * Base namespace root for canonical FHIR model classes (used to register base types).
     */
    private const string MODELS_BASE_NAMESPACE = 'Ardenexal\\FHIRTools\\Component\\Models';

    private Filesystem $filesystem;

    private PackageLoader $packageLoader;

    private ErrorCollector $errorCollector;

    /**
     * One BuilderContext per FHIR version, shared across all processed IG packages.
     * Base types and IG types are all registered here so later packages can extend earlier ones.
     *
     * @var array<string, BuilderContext>
     */
    private array $context;

    public function __construct(
        Filesystem $filesystem,
        PackageLoader $packageLoader,
    ) {
        parent::__construct();
        $this->filesystem     = $filesystem;
        $this->packageLoader  = $packageLoader;
        $this->errorCollector = new ErrorCollector();
        $this->context        = [
            'R4'  => new BuilderContext(),
            'R4B' => new BuilderContext(),
            'R5'  => new BuilderContext(),
        ];
    }

    /**
     * Entry point for `fhir:generate-ig`.
     *
     * @param OutputInterface $output      Console output
     * @param array<string>   $packages    IG packages to generate, e.g. ['hl7.fhir.au.base#1.0.0']
     * @param bool            $offlineMode Use cached packages only — no network
     */
    public function __invoke(
        OutputInterface $output,
        #[Option(description: 'IG packages to generate (specify in dependency order).', name: 'package')]
        array $packages = [],
        #[Option(description: 'Work offline using only cached packages.', name: 'offline')]
        bool $offlineMode = false,
    ): int {
        if (empty($packages)) {
            $output->writeln('<error>No packages specified. Use --package=hl7.fhir.au.base</error>');

            return Command::FAILURE;
        }

        try {
            $this->errorCollector->clear();

            return $this->executeGeneration($output, $packages, $offlineMode);
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
                'R4', 'R4B', 'R5' => true,
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

            $kind = $def['kind'] ?? '';
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
     * Generate extension and profile classes for one IG package and write them to disk.
     *
     * Constraint derivations are routed:
     *   - type=Extension  → FHIRExtensionGenerator → Extension/ subdirectory
     *   - other kinds     → FHIRProfileGenerator   → Profile/ subdirectory
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
        $baseNs          = self::IG_BASE_NAMESPACE . "\\{$version}\\{$slug}";
        $extensionNs     = new PhpNamespace("{$baseNs}\\Extension");
        $profileNs       = new PhpNamespace("{$baseNs}\\Profile");

        $extensionGenerator = new FHIRExtensionGenerator();
        $profileGenerator   = new FHIRProfileGenerator();

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
                    $class = $extensionGenerator->generate($def, $version, $this->context[$version], $extensionNs);
                    $this->context[$version]->addType($url, $extensionNs->getName(), $class);
                    $generated[] = ['class' => $class, 'namespace' => $extensionNs, 'category' => 'Extension'];
                    $output->writeln("    Extension: <comment>{$class->getName()}</comment>");
                } elseif (in_array($kind, ['resource', 'complex-type'], true)) {
                    $class = $profileGenerator->generate($def, $version, $this->context[$version], $profileNs);
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
            "  <info>Generated {$extCount} extension(s) and {$profileCount} profile(s) for {$slug}.</info>"
        );
    }

    /**
     * Write a generated class to disk under Models/src/IG/{version}/{slug}/{category}/.
     */
    private function writeClass(
        OutputInterface $output,
        ClassType $class,
        PhpNamespace $namespace,
        string $version,
        string $slug,
        string $category,
    ): void {
        $basePath   = Path::canonicalize(__DIR__ . '/../../../Models/src');
        $outputPath = Path::canonicalize(
            "{$basePath}/IG/{$version}/{$slug}/{$category}/{$class->getName()}.php"
        );

        $directory = dirname($outputPath);
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->mkdir($directory, 0755);
        }

        $contents = $this->renderPhpFile($class, $namespace->getName());
        $this->filesystem->dumpFile($outputPath, $contents);

        if ($output->isVerbose()) {
            $output->writeln("    Written: {$outputPath}");
        }
    }

    /**
     * Render a ClassType to a PHP file string with strict_types and namespace declaration.
     */
    private function renderPhpFile(ClassType $class, string $namespaceName): string
    {
        $printer = new Printer();

        return <<<PHP
        <?php declare(strict_types=1);

        namespace {$namespaceName};

        {$printer->printClass($class, new PhpNamespace($namespaceName))}
        PHP;
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
}

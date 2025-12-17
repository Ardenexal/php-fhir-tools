<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Package;

use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\PackageException;

/**
 * Handles dependency chain resolution and conflict detection
 *
 * This class provides comprehensive dependency resolution for FHIR packages:
 *
 * - Dependency chain resolution with circular dependency detection
 * - Version conflict detection and resolution
 * - Dependency graph construction and validation
 * - Installation order determination
 * - Conflict reporting with detailed information
 *
 * The resolver uses a topological sort algorithm to determine the correct
 * installation order and detects circular dependencies that would prevent
 * successful package installation.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 *
 * @package Ardenexal\FHIRTools\Component\CodeGeneration
 */
class DependencyResolver
{
    /**
     * Semantic version resolver for version constraint handling
     *
     * @var SemanticVersionResolver
     */
    private SemanticVersionResolver $versionResolver;

    /**
     * Construct a new DependencyResolver
     *
     * @param SemanticVersionResolver $versionResolver Version resolver for constraint handling
     */
    public function __construct(SemanticVersionResolver $versionResolver)
    {
        $this->versionResolver = $versionResolver;
    }

    /**
     * Resolve dependencies for a package and return installation order
     *
     * @param PackageMetadata                           $rootPackage       The root package to resolve dependencies for
     * @param array<string, PackageMetadata>            $availablePackages Available packages indexed by name
     * @param array<string, array<string, string>>|null $packageVersions   Available versions for each package
     *
     * @return array<PackageMetadata> Packages in installation order
     *
     * @throws PackageException When dependency resolution fails
     */
    public function resolveDependencies(
        PackageMetadata $rootPackage,
        array $availablePackages,
        ?array $packageVersions = null
    ): array {
        $resolved = [];
        $visiting = [];
        $visited  = [];

        $this->resolveDependenciesRecursive(
            $rootPackage,
            $availablePackages,
            $packageVersions ?? [],
            $resolved,
            $visiting,
            $visited,
        );

        return $resolved;
    }

    /**
     * Detect version conflicts in a set of packages
     *
     * @param array<PackageMetadata> $packages Packages to check for conflicts
     *
     * @return array<string, array{conflicts: array<string, string>, packages: array<string>}> Conflicts indexed by package name
     */
    public function detectConflicts(array $packages): array
    {
        $conflicts       = [];
        $packageVersions = [];

        // Group packages by name and collect their version requirements
        foreach ($packages as $package) {
            $name = $package->getName();
            if (!isset($packageVersions[$name])) {
                $packageVersions[$name] = [];
            }
            $packageVersions[$name][] = $package->getVersion();

            // Check dependencies for conflicts
            foreach ($package->getDependencies() as $depName => $depConstraint) {
                if (!isset($packageVersions[$depName])) {
                    $packageVersions[$depName] = [];
                }

                // Find packages that provide this dependency
                $providingPackages = array_filter($packages, fn ($p) => $p->getName() === $depName);
                foreach ($providingPackages as $providingPackage) {
                    if (!$this->versionResolver->satisfies($providingPackage->getVersion(), $depConstraint)) {
                        if (!isset($conflicts[$depName])) {
                            $conflicts[$depName] = [
                                'conflicts' => [],
                                'packages'  => [],
                            ];
                        }
                        $conflicts[$depName]['conflicts'][$name] = $depConstraint;
                        $conflicts[$depName]['packages'][]       = $providingPackage->getVersion();
                    }
                }
            }
        }

        return $conflicts;
    }

    /**
     * Build a dependency graph for visualization or analysis
     *
     * @param array<PackageMetadata> $packages Packages to build graph for
     *
     * @return array{nodes: array<string, array{name: string, version: string}>, edges: array<array{from: string, to: string, constraint: string}>}
     */
    public function buildDependencyGraph(array $packages): array
    {
        $nodes = [];
        $edges = [];

        foreach ($packages as $package) {
            $packageId         = $package->getName() . '@' . $package->getVersion();
            $nodes[$packageId] = [
                'name'    => $package->getName(),
                'version' => $package->getVersion(),
            ];

            foreach ($package->getDependencies() as $depName => $depConstraint) {
                // Find the dependency package
                $depPackage = $this->findDependencyPackage($depName, $depConstraint, $packages);
                if ($depPackage !== null) {
                    $depId   = $depPackage->getName() . '@' . $depPackage->getVersion();
                    $edges[] = [
                        'from'       => $packageId,
                        'to'         => $depId,
                        'constraint' => $depConstraint,
                    ];
                }
            }
        }

        return [
            'nodes' => $nodes,
            'edges' => $edges,
        ];
    }

    /**
     * Recursively resolve dependencies with circular dependency detection
     *
     * @param PackageMetadata                      $package           Current package being resolved
     * @param array<string, PackageMetadata>       $availablePackages Available packages
     * @param array<string, array<string, string>> $packageVersions   Available versions
     * @param array<PackageMetadata>               $resolved          Resolved packages (output)
     * @param array<string, bool>                  $visiting          Currently visiting packages (for cycle detection)
     * @param array<string, bool>                  $visited           Already visited packages
     *
     * @throws PackageException When circular dependency is detected
     */
    private function resolveDependenciesRecursive(
        PackageMetadata $package,
        array $availablePackages,
        array $packageVersions,
        array &$resolved,
        array &$visiting,
        array &$visited
    ): void {
        $packageName = $package->getName();

        if (isset($visiting[$packageName])) {
            throw PackageException::dependencyResolutionFailed($packageName, array_keys($visiting));
        }

        if (isset($visited[$packageName])) {
            return;
        }

        $visiting[$packageName] = true;

        foreach ($package->getDependencies() as $depName => $depConstraint) {
            $depPackage = $this->resolveDependencyPackage($depName, $depConstraint, $availablePackages, $packageVersions);

            $this->resolveDependenciesRecursive(
                $depPackage,
                $availablePackages,
                $packageVersions,
                $resolved,
                $visiting,
                $visited,
            );
        }

        unset($visiting[$packageName]);
        $visited[$packageName] = true;
        $resolved[]            = $package;
    }

    /**
     * Resolve a specific dependency package
     *
     * @param string                               $depName           Dependency name
     * @param string                               $depConstraint     Version constraint
     * @param array<string, PackageMetadata>       $availablePackages Available packages
     * @param array<string, array<string, string>> $packageVersions   Available versions
     *
     * @return PackageMetadata The resolved dependency package
     *
     * @throws PackageException When dependency cannot be resolved
     */
    private function resolveDependencyPackage(
        string $depName,
        string $depConstraint,
        array $availablePackages,
        array $packageVersions
    ): PackageMetadata {
        if (!isset($availablePackages[$depName])) {
            throw PackageException::packageNotFound($depName);
        }

        $availableVersions = $packageVersions[$depName] ?? [$availablePackages[$depName]->getVersion()];
        $bestVersion       = $this->versionResolver->resolveBestVersion($depConstraint, $availableVersions);

        // Find the package with the best version
        foreach ($availablePackages as $package) {
            if ($package->getName() === $depName && $package->getVersion() === $bestVersion) {
                return $package;
            }
        }

        throw PackageException::packageNotFound($depName, $bestVersion);
    }

    /**
     * Find a dependency package that satisfies the constraint
     *
     * @param string                 $depName       Dependency name
     * @param string                 $depConstraint Version constraint
     * @param array<PackageMetadata> $packages      Available packages
     *
     * @return PackageMetadata|null The matching package or null if not found
     */
    private function findDependencyPackage(string $depName, string $depConstraint, array $packages): ?PackageMetadata
    {
        foreach ($packages as $package) {
            if ($package->getName() === $depName && $this->versionResolver->satisfies($package->getVersion(), $depConstraint)) {
                return $package;
            }
        }

        return null;
    }
}

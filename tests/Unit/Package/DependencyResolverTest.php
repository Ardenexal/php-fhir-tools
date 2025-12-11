<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Package;

use Ardenexal\FHIRTools\Exception\PackageException;
use Ardenexal\FHIRTools\Package\DependencyResolver;
use Ardenexal\FHIRTools\Package\PackageMetadata;
use Ardenexal\FHIRTools\Package\SemanticVersionResolver;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for DependencyResolver
 *
 * @author FHIR Tools
 */
class DependencyResolverTest extends TestCase
{
    private DependencyResolver $resolver;

    private SemanticVersionResolver $versionResolver;

    protected function setUp(): void
    {
        $this->versionResolver = new SemanticVersionResolver();
        $this->resolver        = new DependencyResolver($this->versionResolver);
    }

    public function testResolveDependenciesWithNoDependencies(): void
    {
        $rootPackage = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Test package',
            author: 'Test Author',
            license: 'MIT',
            dependencies: [],
            title: 'Test Package',
        );

        $result = $this->resolver->resolveDependencies($rootPackage, []);

        self::assertCount(1, $result);
        self::assertSame('test-package', $result[0]->getName());
    }

    public function testResolveDependenciesWithSingleDependency(): void
    {
        $dependency = new PackageMetadata(
            name: 'dependency-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Dependency package',
            author: 'Test Author',
            license: 'MIT',
            dependencies: [],
            title: 'Dependency Package',
        );

        $rootPackage = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Test package',
            author: 'Test Author',
            license: 'MIT',
            dependencies: ['dependency-package' => '^1.0.0'],
            title: 'Test Package',
        );

        $availablePackages = [
            'test-package'       => $rootPackage,
            'dependency-package' => $dependency,
        ];

        $result = $this->resolver->resolveDependencies($rootPackage, $availablePackages);

        self::assertCount(2, $result);
        // Dependencies should be resolved first, then the root package
        self::assertSame('dependency-package', $result[0]->getName());
        self::assertSame('test-package', $result[1]->getName());
    }

    public function testResolveDependenciesThrowsExceptionForCircularDependency(): void
    {
        $packageA = new PackageMetadata(
            name: 'package-a',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Package A',
            author: 'Test Author',
            license: 'MIT',
            dependencies: ['package-b' => '^1.0.0'],
            title: 'Package A',
        );

        $packageB = new PackageMetadata(
            name: 'package-b',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Package B',
            author: 'Test Author',
            license: 'MIT',
            dependencies: ['package-a' => '^1.0.0'],
            title: 'Package B',
        );

        $availablePackages = [
            'package-a' => $packageA,
            'package-b' => $packageB,
        ];

        $this->expectException(PackageException::class);
        $this->expectExceptionMessage('Circular dependency detected');

        $this->resolver->resolveDependencies($packageA, $availablePackages);
    }

    public function testDetectConflictsWithNoConflicts(): void
    {
        $package1 = new PackageMetadata(
            name: 'package-1',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Package 1',
            author: 'Test Author',
            license: 'MIT',
            dependencies: ['shared-dep' => '^1.0.0'],
            title: 'Package 1',
        );

        $package2 = new PackageMetadata(
            name: 'package-2',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Package 2',
            author: 'Test Author',
            license: 'MIT',
            dependencies: ['shared-dep' => '^1.0.0'],
            title: 'Package 2',
        );

        $sharedDep = new PackageMetadata(
            name: 'shared-dep',
            version: '1.2.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Shared dependency',
            author: 'Test Author',
            license: 'MIT',
            dependencies: [],
            title: 'Shared Dependency',
        );

        $packages = [$package1, $package2, $sharedDep];

        $conflicts = $this->resolver->detectConflicts($packages);

        self::assertEmpty($conflicts);
    }

    public function testBuildDependencyGraph(): void
    {
        $dependency = new PackageMetadata(
            name: 'dependency-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Dependency package',
            author: 'Test Author',
            license: 'MIT',
            dependencies: [],
            title: 'Dependency Package',
        );

        $rootPackage = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Test package',
            author: 'Test Author',
            license: 'MIT',
            dependencies: ['dependency-package' => '^1.0.0'],
            title: 'Test Package',
        );

        $packages = [$rootPackage, $dependency];

        $graph = $this->resolver->buildDependencyGraph($packages);

        self::assertArrayHasKey('nodes', $graph);
        self::assertArrayHasKey('edges', $graph);
        self::assertCount(2, $graph['nodes']);
        self::assertCount(1, $graph['edges']);
    }
}

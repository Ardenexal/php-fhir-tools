<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\CodeGeneration\Package;

use Ardenexal\FHIRTools\Component\CodeGeneration\Package\PackageMetadata;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for PackageMetadata
 *
 * @author FHIR Tools
 */
class PackageMetadataTest extends TestCase
{
    /**
     * Test constructor and getter methods
     */
    public function testConstructorAndGetters(): void
    {
        $metadata = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4', 'R4B'],
            url: 'https://example.com/package',
            description: 'Test package description',
            author: 'Test Author',
            license: 'MIT',
            dependencies: ['dep1' => '^1.0.0', 'dep2' => '~2.0.0'],
            title: 'Test Package Title',
            checksum: 'abc123',
            additionalData: ['custom' => 'value'],
        );

        self::assertSame('test-package', $metadata->getName());
        self::assertSame('1.0.0', $metadata->getVersion());
        self::assertSame(['R4', 'R4B'], $metadata->getFhirVersions());
        self::assertSame('https://example.com/package', $metadata->getUrl());
        self::assertSame('Test package description', $metadata->getDescription());
        self::assertSame('Test Author', $metadata->getAuthor());
        self::assertSame('MIT', $metadata->getLicense());
        self::assertSame(['dep1' => '^1.0.0', 'dep2' => '~2.0.0'], $metadata->getDependencies());
        self::assertSame('Test Package Title', $metadata->getTitle());
        self::assertSame('abc123', $metadata->getChecksum());
        self::assertSame(['custom' => 'value'], $metadata->getAdditionalData());
    }

    /**
     * Test fromPackageData factory method
     */
    public function testFromPackageData(): void
    {
        $packageData = [
            'name'         => 'test-package',
            'version'      => '1.0.0',
            'fhirVersions' => ['R4'],
            'url'          => 'https://example.com',
            'description'  => 'Test description',
            'author'       => 'Test Author',
            'license'      => 'MIT',
            'dependencies' => ['dep1' => '^1.0.0'],
            'title'        => 'Test Title',
            'checksum'     => 'abc123',
            'custom_field' => 'custom_value',
        ];

        $metadata = PackageMetadata::fromPackageData($packageData);

        self::assertSame('test-package', $metadata->getName());
        self::assertSame('1.0.0', $metadata->getVersion());
        self::assertSame(['custom_field' => 'custom_value'], $metadata->getAdditionalData());
    }

    /**
     * Test supportsFhirVersion method
     */
    public function testSupportsFhirVersion(): void
    {
        $metadata = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4', 'R4B'],
            url: 'https://example.com',
            description: 'Test description',
            author: 'Test Author',
            license: 'MIT',
            dependencies: [],
            title: 'Test Title',
        );

        self::assertTrue($metadata->supportsFhirVersion('R4'));
        self::assertTrue($metadata->supportsFhirVersion('R4B'));
        self::assertFalse($metadata->supportsFhirVersion('R5'));
    }

    /**
     * Test hasDependencies method
     */
    public function testHasDependencies(): void
    {
        $withDeps = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Test description',
            author: 'Test Author',
            license: 'MIT',
            dependencies: ['dep1' => '^1.0.0'],
            title: 'Test Title',
        );

        $withoutDeps = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Test description',
            author: 'Test Author',
            license: 'MIT',
            dependencies: [],
            title: 'Test Title',
        );

        self::assertTrue($withDeps->hasDependencies());
        self::assertFalse($withoutDeps->hasDependencies());
    }

    /**
     * Test getDependencyConstraint method
     */
    public function testGetDependencyConstraint(): void
    {
        $metadata = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Test description',
            author: 'Test Author',
            license: 'MIT',
            dependencies: ['dep1' => '^1.0.0', 'dep2' => '~2.0.0'],
            title: 'Test Title',
        );

        self::assertSame('^1.0.0', $metadata->getDependencyConstraint('dep1'));
        self::assertSame('~2.0.0', $metadata->getDependencyConstraint('dep2'));
        self::assertNull($metadata->getDependencyConstraint('nonexistent'));
    }

    /**
     * Test hasDependency method
     */
    public function testHasDependency(): void
    {
        $metadata = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Test description',
            author: 'Test Author',
            license: 'MIT',
            dependencies: ['dep1' => '^1.0.0'],
            title: 'Test Title',
        );

        self::assertTrue($metadata->hasDependency('dep1'));
        self::assertFalse($metadata->hasDependency('nonexistent'));
    }

    /**
     * Test getIdentifier method
     */
    public function testGetIdentifier(): void
    {
        $metadata = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Test description',
            author: 'Test Author',
            license: 'MIT',
            dependencies: [],
            title: 'Test Title',
        );

        self::assertSame('test-package@1.0.0', $metadata->getIdentifier());
    }

    /**
     * Test toArray method
     */
    public function testToArray(): void
    {
        $metadata = new PackageMetadata(
            name: 'test-package',
            version: '1.0.0',
            fhirVersions: ['R4'],
            url: 'https://example.com',
            description: 'Test description',
            author: 'Test Author',
            license: 'MIT',
            dependencies: ['dep1' => '^1.0.0'],
            title: 'Test Title',
            checksum: 'abc123',
            additionalData: ['custom' => 'value'],
        );

        $array = $metadata->toArray();
        self::assertSame('test-package', $array['name']);
        self::assertSame('1.0.0', $array['version']);
        self::assertSame(['R4'], $array['fhirVersions']);
        self::assertSame(['dep1' => '^1.0.0'], $array['dependencies']);
        self::assertSame('abc123', $array['checksum']);
        self::assertSame(['custom' => 'value'], $array['additionalData']);
    }
}

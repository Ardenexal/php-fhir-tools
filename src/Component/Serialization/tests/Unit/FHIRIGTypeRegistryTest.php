<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

class FHIRIGTypeRegistryTest extends TestCase
{
    public function testResolveExtensionClassReturnsClassForKnownUrl(): void
    {
        $registry = new FHIRIGTypeRegistry(
            extensionMappings: [
                'http://hl7.org/fhir/StructureDefinition/patient-birthPlace' => \stdClass::class,
            ],
        );

        self::assertSame(
            \stdClass::class,
            $registry->resolveExtensionClass('http://hl7.org/fhir/StructureDefinition/patient-birthPlace'),
        );
    }

    public function testResolveExtensionClassReturnsNullForUnknownUrl(): void
    {
        $registry = new FHIRIGTypeRegistry();

        self::assertNull($registry->resolveExtensionClass('http://example.com/unknown'));
    }

    public function testResolveProfileClassReturnsClassForKnownProfileUrl(): void
    {
        $registry = new FHIRIGTypeRegistry(
            profileMappings: [
                'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient' => \stdClass::class,
            ],
        );

        self::assertSame(
            \stdClass::class,
            $registry->resolveProfileClass('http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'),
        );
    }

    public function testResolveProfileClassReturnsNullForUnknownProfileUrl(): void
    {
        $registry = new FHIRIGTypeRegistry();

        self::assertNull($registry->resolveProfileClass('http://example.com/unknown-profile'));
    }

    public function testGetExtensionMappingsReturnsMap(): void
    {
        $map      = ['http://example.com/ext' => \stdClass::class];
        $registry = new FHIRIGTypeRegistry(extensionMappings: $map);

        self::assertSame($map, $registry->getExtensionMappings());
    }

    public function testGetProfileMappingsReturnsMap(): void
    {
        $map      = ['http://example.com/profile' => \stdClass::class];
        $registry = new FHIRIGTypeRegistry(profileMappings: $map);

        self::assertSame($map, $registry->getProfileMappings());
    }

    public function testEmptyRegistryReturnsBothEmptyMaps(): void
    {
        $registry = new FHIRIGTypeRegistry();

        self::assertSame([], $registry->getExtensionMappings());
        self::assertSame([], $registry->getProfileMappings());
    }
}

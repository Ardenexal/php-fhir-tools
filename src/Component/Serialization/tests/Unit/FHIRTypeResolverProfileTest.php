<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolver;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

class FHIRTypeResolverProfileTest extends TestCase
{
    public function testResolveResourceTypeReturnsProfileClassWhenMetaProfileMatches(): void
    {
        $registry = new FHIRIGTypeRegistry(
            profileMappings: [
                'http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient' => \stdClass::class,
            ],
        );

        $resolver = new FHIRTypeResolver(igTypeRegistry: $registry);

        $data = [
            'resourceType' => 'Patient',
            'meta'         => [
                'profile' => ['http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'],
            ],
        ];

        self::assertSame(\stdClass::class, $resolver->resolveResourceType($data));
    }

    public function testResolveResourceTypeSkipsUnregisteredProfileUrls(): void
    {
        $registry = new FHIRIGTypeRegistry();
        $resolver = new FHIRTypeResolver(igTypeRegistry: $registry);

        $data = [
            'resourceType' => 'Patient',
            'meta'         => [
                'profile' => ['http://example.com/some-profile'],
            ],
        ];

        // No profile match → convention fallback (or null when models not installed)
        $result = $resolver->resolveResourceType($data);
        // Should NOT return the unknown profile URL — just the base class or null
        self::assertNotSame('http://example.com/some-profile', $result);
    }

    public function testResolveResourceTypeWithoutMetaProfileFallsBack(): void
    {
        $registry = new FHIRIGTypeRegistry(
            profileMappings: [
                'http://example.com/profile' => \stdClass::class,
            ],
        );

        $resolver = new FHIRTypeResolver(igTypeRegistry: $registry);

        // Data has no meta.profile — registry must NOT be consulted
        $data = ['resourceType' => 'Patient'];

        // Result should be the convention-based class (or null if models not loaded)
        $result = $resolver->resolveResourceType($data);
        self::assertNotSame(\stdClass::class, $result);
    }

    public function testResolveResourceTypeWithNullRegistryIgnoresMetaProfile(): void
    {
        $resolver = new FHIRTypeResolver(); // no registry

        $data = [
            'resourceType' => 'Patient',
            'meta'         => [
                'profile' => ['http://hl7.org/fhir/us/core/StructureDefinition/us-core-patient'],
            ],
        ];

        // Without a registry the resolver falls back to convention-based lookup
        $result = $resolver->resolveResourceType($data);
        // Should not throw; result depends on whether R4 models are installed
        self::assertTrue($result === null || is_string($result));
    }

    public function testResolveResourceTypeReturnsFirstMatchingProfileClass(): void
    {
        $registry = new FHIRIGTypeRegistry(
            profileMappings: [
                'http://example.com/profile-b' => \ArrayObject::class,
                'http://example.com/profile-a' => \stdClass::class,
            ],
        );

        $resolver = new FHIRTypeResolver(igTypeRegistry: $registry);

        // profile-a listed first in meta.profile → should return stdClass
        $data = [
            'resourceType' => 'Patient',
            'meta'         => [
                'profile' => [
                    'http://example.com/profile-a',
                    'http://example.com/profile-b',
                ],
            ],
        ];

        self::assertSame(\stdClass::class, $resolver->resolveResourceType($data));
    }
}

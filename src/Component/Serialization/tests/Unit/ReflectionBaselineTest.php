<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Models\R4\Profile\ShareableActivityDefinitionProfile;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ActivityDefinitionResource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRSerializedTypeResolver;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Pins type-resolution behaviour before the registry consolidation moves this class into Metadata.
 *
 * Two resolvers, opposite roles, both green today:
 *
 *  - The PROFILE-AWARE one mirrors how FHIRSerializationService::createWithIG builds it (search:
 *    `igTypeRegistry:`) — registry and version both supplied. This is the behaviour the relocation
 *    must not lose, so these tests must FAIL if profile visibility regresses.
 *  - The PROFILE-BLIND one mirrors the container registration, which supplies no arguments at all.
 *    Its answers are wrong, and deliberately pinned so the later wiring fix shows up as a diff
 *    rather than as a silent improvement. Tests marked PINS-A-DEFECT below are EXPECTED to change.
 *
 * A fixture built only through the container would pin the blind answers and could never detect the
 * thing it exists to detect, because that path never had profile visibility to lose.
 */
class ReflectionBaselineTest extends TestCase
{
    private const PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareableactivitydefinition';

    /**
     * @return array<string, mixed>
     */
    private static function profiledPayload(): array
    {
        return [
            'resourceType' => 'ActivityDefinition',
            'meta'         => ['profile' => [self::PROFILE_URL]],
        ];
    }

    private static function profileAwareResolver(): FHIRSerializedTypeResolver
    {
        $registry = new FHIRIGTypeRegistry(
            profileMappings: [self::PROFILE_URL => ShareableActivityDefinitionProfile::class],
        );

        return new FHIRSerializedTypeResolver(igTypeRegistry: $registry, fhirVersion: 'R4');
    }

    public function testProfileAwareResolverReturnsTheProfileSubclass(): void
    {
        $resolved = self::profileAwareResolver()->resolveResourceType(self::profiledPayload());

        self::assertSame(ShareableActivityDefinitionProfile::class, $resolved);
    }

    /**
     * The falsifiability check for the profile-aware fixture above.
     *
     * Withholding only the registry must change the answer to the base resource. If this ever stops
     * differing from the test above, the fixture has stopped measuring profile visibility and every
     * "behaviour unchanged" claim resting on it is void.
     */
    public function testWithholdingTheRegistryLosesTheProfileAndReturnsTheBaseResource(): void
    {
        $withRegistry    = self::profileAwareResolver()->resolveResourceType(self::profiledPayload());
        $withoutRegistry = (new FHIRSerializedTypeResolver(fhirVersion: 'R4'))
            ->resolveResourceType(self::profiledPayload());

        self::assertSame(ShareableActivityDefinitionProfile::class, $withRegistry);
        self::assertSame(ActivityDefinitionResource::class, $withoutRegistry);
        self::assertNotSame($withRegistry, $withoutRegistry, 'the fixture must be able to detect lost profile visibility');
    }

    public function testProfileAwareResolverIsScopedToItsConfiguredVersion(): void
    {
        $resolved = self::profileAwareResolver()->resolveResourceType(['resourceType' => 'Patient']);

        self::assertSame('Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource', $resolved);
    }

    /**
     * PINS-A-DEFECT. The container registers this class with no arguments, so meta.profile is never
     * consulted. Expected to change when the wiring is fixed.
     */
    public function testContainerStyleResolverIgnoresMetaProfileEntirely(): void
    {
        $resolved = (new FHIRSerializedTypeResolver())->resolveResourceType(self::profiledPayload());

        self::assertSame(ActivityDefinitionResource::class, $resolved);
        self::assertNotSame(ShareableActivityDefinitionProfile::class, $resolved);
    }

    /**
     * PINS-A-DEFECT. With no configured version the resolver scans R4, R4B then R5 and returns the
     * first hit, so every version's stack resolves to R4 for any type present in more than one.
     * Expected to change when the wiring is fixed.
     */
    public function testContainerStyleResolverReturnsR4ForATypePresentInEveryVersion(): void
    {
        $resolved = (new FHIRSerializedTypeResolver())->resolveResourceType(['resourceType' => 'Patient']);

        self::assertSame('Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource', $resolved);

        foreach (['R4B', 'R5'] as $laterVersion) {
            self::assertTrue(
                class_exists("Ardenexal\\FHIRTools\\Component\\Models\\{$laterVersion}\\Resource\\PatientResource"),
                "{$laterVersion} Patient must exist for this to pin a real ambiguity",
            );
        }
    }
}

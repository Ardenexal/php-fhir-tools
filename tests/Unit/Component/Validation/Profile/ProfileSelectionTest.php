<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Component\Validation\Profile;

use Ardenexal\FHIRTools\Component\Validation\Profile\ProfileSelection;
use PHPUnit\Framework\TestCase;

/**
 * Test ProfileSelection functionality.
 *
 * @author FHIR Tools
 */
class ProfileSelectionTest extends TestCase
{
    public function testResolveUsesExplicitProfilesFirst(): void
    {
        $selector = new ProfileSelection();

        $resource = [
            'resourceType' => 'Patient',
            'meta' => [
                'profile' => ['http://example.org/StructureDefinition/MetaProfile'],
            ],
        ];

        $explicit = ['http://example.org/StructureDefinition/ExplicitProfile'];

        $result = $selector->resolve($resource, $explicit);

        self::assertSame(['http://example.org/StructureDefinition/ExplicitProfile'], $result);
    }

    public function testResolveUsesMetaProfilesWhenNoExplicit(): void
    {
        $selector = new ProfileSelection();

        $resource = [
            'resourceType' => 'Patient',
            'meta' => [
                'profile' => [
                    'http://example.org/StructureDefinition/Profile1',
                    'http://example.org/StructureDefinition/Profile2',
                ],
            ],
        ];

        $result = $selector->resolve($resource);

        self::assertCount(2, $result);
        self::assertContains('http://example.org/StructureDefinition/Profile1', $result);
        self::assertContains('http://example.org/StructureDefinition/Profile2', $result);
    }

    public function testResolveUsesDefaultProfilesWhenNoMetaAndNoExplicit(): void
    {
        $selector = new ProfileSelection([
            'Patient' => 'http://hl7.org/fhir/StructureDefinition/Patient',
            'Observation' => [
                'http://hl7.org/fhir/StructureDefinition/Observation',
                'http://example.org/StructureDefinition/CustomObservation',
            ],
        ]);

        $patientResource = ['resourceType' => 'Patient'];
        $result = $selector->resolve($patientResource);

        self::assertSame(['http://hl7.org/fhir/StructureDefinition/Patient'], $result);

        $observationResource = ['resourceType' => 'Observation'];
        $result = $selector->resolve($observationResource);

        self::assertCount(2, $result);
        self::assertContains('http://hl7.org/fhir/StructureDefinition/Observation', $result);
    }

    public function testResolveReturnsEmptyWhenNoProfilesAvailable(): void
    {
        $selector = new ProfileSelection();

        $resource = ['resourceType' => 'Patient'];

        $result = $selector->resolve($resource);

        self::assertEmpty($result);
    }

    public function testResolveDeduplicatesProfiles(): void
    {
        $selector = new ProfileSelection();

        $resource = [
            'resourceType' => 'Patient',
        ];

        $explicit = [
            'http://example.org/StructureDefinition/Profile1',
            'http://example.org/StructureDefinition/Profile1', // duplicate
            'http://example.org/StructureDefinition/Profile2',
        ];

        $result = $selector->resolve($resource, $explicit);

        self::assertCount(2, $result);
        self::assertContains('http://example.org/StructureDefinition/Profile1', $result);
        self::assertContains('http://example.org/StructureDefinition/Profile2', $result);
    }

    public function testResolveFiltersEmptyStringsFromMetaProfiles(): void
    {
        $selector = new ProfileSelection();

        $resource = [
            'resourceType' => 'Patient',
            'meta' => [
                'profile' => [
                    'http://example.org/StructureDefinition/Valid',
                    '', // empty string
                    'http://example.org/StructureDefinition/AlsoValid',
                ],
            ],
        ];

        $result = $selector->resolve($resource);

        self::assertCount(2, $result);
        self::assertNotContains('', $result);
    }

    public function testHasMetaProfilesReturnsTrueWhenPresent(): void
    {
        $selector = new ProfileSelection();

        $resource = [
            'resourceType' => 'Patient',
            'meta' => [
                'profile' => ['http://example.org/StructureDefinition/Profile'],
            ],
        ];

        self::assertTrue($selector->hasMetaProfiles($resource));
    }

    public function testHasMetaProfilesReturnsFalseWhenAbsent(): void
    {
        $selector = new ProfileSelection();

        $resource = ['resourceType' => 'Patient'];

        self::assertFalse($selector->hasMetaProfiles($resource));
    }

    public function testGetDefaultProfilesForTypeReturnsConfigured(): void
    {
        $selector = new ProfileSelection([
            'Patient' => 'http://hl7.org/fhir/StructureDefinition/Patient',
            'Observation' => [
                'http://hl7.org/fhir/StructureDefinition/Observation',
                'http://example.org/StructureDefinition/CustomObservation',
            ],
        ]);

        $result = $selector->getDefaultProfilesForType('Patient');
        self::assertSame(['http://hl7.org/fhir/StructureDefinition/Patient'], $result);

        $result = $selector->getDefaultProfilesForType('Observation');
        self::assertCount(2, $result);

        $result = $selector->getDefaultProfilesForType('Unknown');
        self::assertNull($result);
    }

    public function testHasProfilesReturnsTrueWhenProfilesAvailable(): void
    {
        $selector = new ProfileSelection([
            'Patient' => 'http://hl7.org/fhir/StructureDefinition/Patient',
        ]);

        $resource = ['resourceType' => 'Patient'];

        self::assertTrue($selector->hasProfiles($resource));
    }

    public function testHasProfilesReturnsFalseWhenNoProfilesAvailable(): void
    {
        $selector = new ProfileSelection();

        $resource = ['resourceType' => 'Patient'];

        self::assertFalse($selector->hasProfiles($resource));
    }

    public function testResolveHandlesNonArrayMetaProfile(): void
    {
        $selector = new ProfileSelection();

        $resource = [
            'resourceType' => 'Patient',
            'meta' => [
                'profile' => 'not-an-array', // Invalid: should be array
            ],
        ];

        $result = $selector->resolve($resource);

        self::assertEmpty($result);
    }

    public function testResolveHandlesEmptyExplicitArray(): void
    {
        $selector = new ProfileSelection([
            'Patient' => 'http://hl7.org/fhir/StructureDefinition/Patient',
        ]);

        $resource = ['resourceType' => 'Patient'];
        $explicit = [];

        $result = $selector->resolve($resource, $explicit);

        // Empty explicit array means "no explicit profiles", should fall back to defaults
        self::assertSame(['http://hl7.org/fhir/StructureDefinition/Patient'], $result);
    }
}

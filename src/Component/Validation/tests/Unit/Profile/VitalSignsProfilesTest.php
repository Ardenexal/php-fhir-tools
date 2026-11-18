<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Unit\Profile;

use Ardenexal\FHIRTools\Component\Validation\Profile\VitalSignsProfiles;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(VitalSignsProfiles::class)]
final class VitalSignsProfilesTest extends TestCase
{
    public function testALoincVitalSignCodeImpliesItsProfile(): void
    {
        self::assertSame(
            ['http://hl7.org/fhir/StructureDefinition/bodyweight'],
            VitalSignsProfiles::forCodings([['system' => 'http://loinc.org', 'code' => '29463-7']]),
        );
    }

    public function testTheSnomedBodyTemperatureCodeImpliesBodytemp(): void
    {
        self::assertSame(
            ['http://hl7.org/fhir/StructureDefinition/bodytemp'],
            VitalSignsProfiles::forCodings([['system' => 'http://snomed.info/sct', 'code' => '276885007']]),
        );
    }

    public function testTheAlternateLoincCodesShareTheirProfile(): void
    {
        self::assertSame(
            ['http://hl7.org/fhir/StructureDefinition/oxygensat', 'http://hl7.org/fhir/StructureDefinition/bp'],
            VitalSignsProfiles::forCodings([
                ['system' => 'http://loinc.org', 'code' => '2708-6'],
                ['system' => 'http://loinc.org', 'code' => '59408-5'],
                ['system' => 'http://loinc.org', 'code' => '76534-7'],
            ]),
        );
    }

    public function testASnomedCodeInTheLoincSystemImpliesNothing(): void
    {
        self::assertSame([], VitalSignsProfiles::forCodings([['system' => 'http://loinc.org', 'code' => '276885007']]));
    }

    public function testOtherSystemsAndUnknownCodesImplyNothing(): void
    {
        self::assertSame([], VitalSignsProfiles::forCodings([
            ['system' => 'http://snomed.info/sct', 'code' => '29463-7'],
            ['system' => null, 'code' => '29463-7'],
            ['system' => 'http://loinc.org', 'code' => null],
            ['system' => 'http://loinc.org', 'code' => '8480-6'],
        ]));
    }

    public function testEachProfileIsListedOnceInCodingOrder(): void
    {
        self::assertSame(
            ['http://hl7.org/fhir/StructureDefinition/heartrate', 'http://hl7.org/fhir/StructureDefinition/bp'],
            VitalSignsProfiles::forCodings([
                ['system' => 'http://loinc.org', 'code' => '8867-4'],
                ['system' => 'http://loinc.org', 'code' => '85354-9'],
                ['system' => 'http://loinc.org', 'code' => '8867-4'],
            ]),
        );
    }
}

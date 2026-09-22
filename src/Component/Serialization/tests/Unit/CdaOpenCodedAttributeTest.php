<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuParticipant2;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Observation;
use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Participant2;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\AuPersonName;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\EntityNameUse;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\ParticipationType;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * Open coded CDA attributes (`propertyKind: 'openEnum'`) round-trip through the XML normalizer.
 *
 * The AU profiles rebind some inherited coded elements more widely than core CDA, so the generated
 * property is `Enum|string`: a code the enum knows comes back as its case, any other code as the
 * bare string rather than failing. Regression cover for issue #134 (`typeCode="CAGNT"`).
 */
final class CdaOpenCodedAttributeTest extends TestCase
{
    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createWithIG(version: FhirVersion::R5);
    }

    public function testCodeOutsideTheEnumRoundTripsAsAString(): void
    {
        $service = $this->service();

        $xml = $service->serializeToXml(new AuParticipant2(typeCode: 'CAGNT'));
        self::assertStringContainsString('typeCode="CAGNT"', $xml);

        $decoded = $service->deserializeFromXml($xml, AuParticipant2::class);
        self::assertInstanceOf(AuParticipant2::class, $decoded);
        self::assertSame('CAGNT', $decoded->typeCode);
    }

    public function testCodeInTheEnumStillRoundTripsAsTheCase(): void
    {
        $service = $this->service();

        $xml     = $service->serializeToXml(new AuParticipant2(typeCode: ParticipationType::csm));
        $decoded = $service->deserializeFromXml($xml, AuParticipant2::class);

        self::assertInstanceOf(AuParticipant2::class, $decoded);
        self::assertSame(ParticipationType::csm, $decoded->typeCode);
    }

    public function testNestedParticipantKeepsBothShapes(): void
    {
        $service = $this->service();

        // Where the issue actually bites: a causative agent inside an allergy observation.
        $xml = $service->serializeToXml(new Observation(participant: [
            new Participant2(typeCode: 'CAGNT'),
            new Participant2(typeCode: ParticipationType::csm),
        ]));

        $decoded = $service->deserializeFromXml($xml, Observation::class);
        self::assertInstanceOf(Observation::class, $decoded);
        self::assertCount(2, $decoded->participant);
        self::assertSame('CAGNT', $decoded->participant[0]->typeCode);
        self::assertSame(ParticipationType::csm, $decoded->participant[1]->typeCode);
    }

    public function testOpenCodedListMixesCasesAndStrings(): void
    {
        $service = $this->service();

        // NB (newborn) comes from the ADHA dh-entitynameuse set, which core CDAEntityNameUse lacks.
        $xml = $service->serializeToXml(new AuPersonName(use: ['NB', EntityNameUse::l]));
        self::assertStringContainsString('use="NB L"', $xml);

        $decoded = $service->deserializeFromXml($xml, AuPersonName::class);
        self::assertInstanceOf(AuPersonName::class, $decoded);
        self::assertSame(['NB', EntityNameUse::l], $decoded->use);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Fixtures\FixtureBinaryDataEncoding;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Fixtures\FixtureEncodedData;
use Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Fixtures\FixturePostalAddressUse;
use PHPUnit\Framework\TestCase;

/**
 * Enum-typed xmlAttr properties round-trip through the XML normalizer stack.
 *
 * Regression cover for enum-typed properties reaching the generic normalizer chain ("no supporting
 * normalizer found") on the way out, and being assigned as raw strings (TypeError) on the way back
 * in. CDA is the real-world consumer — 227 of 260 generated CDA classes declare an enum-typed
 * property — but the CDA models are a separate Composer package (ADR-009), so these cases drive the
 * normalizer through a local fixture complex type instead.
 */
final class EnumXmlAttributeSerializationTest extends TestCase
{
    private function service(): FHIRSerializationService
    {
        return FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testEnumXmlAttributeEmitsBackingValueNotCaseName(): void
    {
        $xml = $this->service()->serializeToXml(new FixtureEncodedData(
            mediaType: 'application/pdf',
            representation: FixtureBinaryDataEncoding::base64_encodedtext,
        ));

        self::assertStringContainsString('representation="B64"', $xml);
        self::assertStringNotContainsString('base64_encodedtext', $xml);
    }

    public function testPlainStringXmlAttributeStillEmits(): void
    {
        $xml = $this->service()->serializeToXml(new FixtureEncodedData(
            mediaType: 'application/pdf',
            representation: FixtureBinaryDataEncoding::base64_encodedtext,
        ));

        self::assertStringContainsString('mediaType="application/pdf"', $xml);
    }

    public function testEnumListXmlAttributeEmitsSpaceDelimitedCodes(): void
    {
        $xml = $this->service()->serializeToXml(new FixtureEncodedData(
            use: [FixturePostalAddressUse::home, FixturePostalAddressUse::work],
        ));

        self::assertStringContainsString('use="H WP"', $xml);
    }

    public function testRoundTripReconstructsTheEnumInstance(): void
    {
        $service = $this->service();

        $xml = $service->serializeToXml(new FixtureEncodedData(
            mediaType: 'application/pdf',
            representation: FixtureBinaryDataEncoding::base64_encodedtext,
        ));

        $decoded = $service->deserializeFromXml($xml, FixtureEncodedData::class);

        self::assertInstanceOf(FixtureEncodedData::class, $decoded);
        self::assertSame(FixtureBinaryDataEncoding::base64_encodedtext, $decoded->representation);
        self::assertSame('application/pdf', $decoded->mediaType);
    }

    public function testRoundTripReconstructsAnEnumList(): void
    {
        $service = $this->service();

        $xml = $service->serializeToXml(new FixtureEncodedData(
            use: [FixturePostalAddressUse::home, FixturePostalAddressUse::work],
        ));

        $decoded = $service->deserializeFromXml($xml, FixtureEncodedData::class);

        self::assertInstanceOf(FixtureEncodedData::class, $decoded);
        self::assertSame(
            [FixturePostalAddressUse::home, FixturePostalAddressUse::work],
            $decoded->use,
        );
    }

    public function testUnrecognisedCodeFailsLoudlyRatherThanYieldingNull(): void
    {
        $xml = '<FixtureEncodedData representation="NOT_A_CODE"/>';

        $this->expectException(FHIRSerializationException::class);
        $this->expectExceptionMessageMatches('/NOT_A_CODE/');

        $this->service()->deserializeFromXml($xml, FixtureEncodedData::class);
    }
}

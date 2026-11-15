<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\MalformedPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\MalformedPrimitiveRecorder;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\BooleanPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * An extension on a primitive survives a JSON round trip, whether or not the primitive has a value.
 *
 * SDC templates are made of extension-only primitives (`"_value": {"extension": [...]}`), so a lost
 * `_x` key silently drops the template's instructions. The assertions are document in, document out,
 * because that is where the loss is visible (#207).
 *
 * @author Ardenexal
 */
class PrimitiveExtensionRoundTripTest extends TestCase
{
    private const array EXTENSION = [
        'extension' => [
            ['url' => 'http://example.org/StructureDefinition/x', 'valueString' => 'expr'],
        ],
    ];

    /**
     * An extension-only primitive is written back with its `_x` key and no `"x": null`.
     *
     * The normalizers wrote `$data[$key] = $normalized['value']` unconditionally, so an absent value
     * became `"text": null` (invalid FHIR JSON) and raised an "Undefined array key" warning.
     */
    public function testExtensionOnlyStringIsWrittenWithoutANullValue(): void
    {
        $document = [
            'resourceType' => 'Patient',
            'name'         => [['_text' => self::EXTENSION]],
        ];

        $output = $this->roundTrip($document);

        self::assertSame($document, $output);
    }

    /**
     * A choice variant keeps its extension, whatever primitive type it is.
     *
     * `_valueString` names a variant rather than a property, so the second pass found no metadata
     * for it and dropped the extension, for every primitive type, in JSON only.
     *
     * @param array<string, mixed> $document
     */
    #[DataProvider('choicePrimitiveDocuments')]
    public function testExtensionOnChoicePrimitiveSurvives(array $document): void
    {
        self::assertSame($document, $this->roundTrip($document));
    }

    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function choicePrimitiveDocuments(): iterable
    {
        $observation = ['resourceType' => 'Observation', 'status' => 'final', 'code' => ['text' => 'x']];

        yield 'string variant with value and extension' => [
            $observation + ['valueString' => 'a', '_valueString' => self::EXTENSION],
        ];

        yield 'extension-only string variant' => [
            $observation + ['_valueString' => self::EXTENSION],
        ];

        yield 'extension-only dateTime variant' => [
            $observation + ['_effectiveDateTime' => self::EXTENSION],
        ];

        yield 'extension-only value on an extension' => [[
            'resourceType' => 'Patient',
            'extension'    => [['url' => 'http://example.org/StructureDefinition/y', '_valueString' => self::EXTENSION]],
        ]];
    }

    /**
     * `boolean`, `integer` and `decimal` elements keep their extension, with and without a value.
     *
     * @param array<string, mixed> $document
     */
    #[DataProvider('scalarPrimitiveDocuments')]
    public function testExtensionOnScalarBackedPrimitiveSurvives(array $document): void
    {
        self::assertEquals($document, $this->roundTrip($document));
    }

    /**
     * @return iterable<string, array{array<string, mixed>}>
     */
    public static function scalarPrimitiveDocuments(): iterable
    {
        yield 'boolean with value and extension' => [[
            'resourceType' => 'Patient',
            'active'       => true,
            '_active'      => self::EXTENSION,
        ]];

        yield 'extension-only boolean' => [[
            'resourceType' => 'Patient',
            '_active'      => self::EXTENSION,
        ]];

        yield 'extension-only boolean choice variant' => [[
            'resourceType'  => 'Observation',
            'status'        => 'final',
            'code'          => ['text' => 'x'],
            '_valueBoolean' => self::EXTENSION,
        ]];

        yield 'extension-only decimal in a complex type' => [[
            'resourceType'  => 'Observation',
            'status'        => 'final',
            'code'          => ['text' => 'x'],
            'valueQuantity' => ['unit' => 'cm', '_value' => self::EXTENSION],
        ]];

        yield 'decimal with value and extension' => [[
            'resourceType'  => 'Observation',
            'status'        => 'final',
            'code'          => ['text' => 'x'],
            'valueQuantity' => ['value' => 1.5, '_value' => self::EXTENSION],
        ]];

        yield 'integer with value and extension in a backbone element' => [[
            'resourceType' => 'Questionnaire',
            'status'       => 'draft',
            'item'         => [[
                'linkId'     => 'q1',
                'type'       => 'string',
                'maxLength'  => 10,
                '_maxLength' => self::EXTENSION,
            ]],
        ]];

        yield 'extension-only decimal in a contained resource' => [[
            'resourceType' => 'Questionnaire',
            'status'       => 'draft',
            'contained'    => [[
                'resourceType'  => 'Observation',
                'status'        => 'final',
                'code'          => ['text' => 'x'],
                'valueQuantity' => ['_value' => self::EXTENSION],
            ]],
        ]];
    }

    /**
     * A plain value stays a native scalar; only an extension brings in the wrapper.
     *
     * Wrapping every value would break each `=== true` and numeric read on these properties.
     */
    public function testPlainScalarValuesStayScalarAndExtendedOnesAreWrapped(): void
    {
        $service = FHIRSerializationService::createDefault();

        $plain = $service->deserialize('{"resourceType":"Patient","active":false}');
        self::assertInstanceOf(PatientResource::class, $plain);
        self::assertFalse($plain->active);

        $plainXml = $service->deserialize('<Patient xmlns="http://hl7.org/fhir"><active value="false"/></Patient>');
        self::assertInstanceOf(PatientResource::class, $plainXml);
        self::assertFalse($plainXml->active);

        $extended = $service->deserialize(json_encode(
            ['resourceType' => 'Patient', 'active' => false, '_active' => self::EXTENSION],
            JSON_THROW_ON_ERROR,
        ));
        self::assertInstanceOf(PatientResource::class, $extended);
        self::assertInstanceOf(BooleanPrimitive::class, $extended->active);
        self::assertFalse($extended->active->value);
        self::assertCount(1, $extended->active->extension);
    }

    /**
     * XML keeps the extension on `boolean`, `integer` and `decimal` elements, choice variants included.
     *
     * An extension-only `<valueBoolean>` used to come back as `value="false"`: a value the document
     * never had.
     */
    #[DataProvider('scalarPrimitiveXmlDocuments')]
    public function testExtensionOnScalarBackedPrimitiveSurvivesXml(string $document): void
    {
        $service = FHIRSerializationService::createDefault();

        self::assertXmlStringEqualsXmlString($document, $service->serializeToXml($service->deserialize($document)));
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function scalarPrimitiveXmlDocuments(): iterable
    {
        $extension = '<extension url="http://example.org/StructureDefinition/x"><valueString value="expr"/></extension>';
        $obs       = '<status value="final"/><code><text value="x"/></code>';

        yield 'boolean with value and extension' => [
            '<Patient xmlns="http://hl7.org/fhir"><active value="true">' . $extension . '</active></Patient>',
        ];

        yield 'extension-only boolean choice variant' => [
            '<Observation xmlns="http://hl7.org/fhir">' . $obs . '<valueBoolean>' . $extension . '</valueBoolean></Observation>',
        ];

        yield 'decimal in a complex type' => [
            '<Observation xmlns="http://hl7.org/fhir">' . $obs
            . '<valueQuantity><value value="1.50">' . $extension . '</value><unit value="cm"/></valueQuantity></Observation>',
        ];

        yield 'integer in a backbone element' => [
            '<Questionnaire xmlns="http://hl7.org/fhir"><status value="draft"/><item><linkId value="q"/>'
            . '<type value="string"/><maxLength value="3">' . $extension . '</maxLength></item></Questionnaire>',
        ];
    }

    /**
     * A malformed XML boolean is still recorded for validation when it carries an extension.
     *
     * The extended element skips the scalar branch that records it, so `value="TRUE"` would pass
     * validation only because an extension sat beside it.
     */
    public function testMalformedXmlBooleanWithExtensionIsStillRecorded(): void
    {
        $patient = FHIRSerializationService::createDefault()->deserialize(
            '<Patient xmlns="http://hl7.org/fhir"><active value="TRUE">'
            . '<extension url="http://example.org/StructureDefinition/x"><valueString value="expr"/></extension>'
            . '</active></Patient>',
        );

        $recorded = MalformedPrimitiveRecorder::forObject($patient);

        self::assertCount(1, $recorded);
        self::assertSame(MalformedPrimitive::KIND_BOOLEAN, $recorded[0]->kind);
        self::assertSame('TRUE', $recorded[0]->lexeme);
    }

    /**
     * @param array<string, mixed> $document
     *
     * @return array<string, mixed>
     */
    private function roundTrip(array $document): array
    {
        $service = FHIRSerializationService::createDefault();
        $object  = $service->deserialize(json_encode($document, JSON_THROW_ON_ERROR));

        $decoded = json_decode($service->serializeToJson($object), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($decoded);

        /** @var array<string, mixed> $decoded */
        return $decoded;
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

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

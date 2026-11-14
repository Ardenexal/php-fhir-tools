<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationOptions;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRFixedValueValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Fixed values (StructureDefinition `fixed[x]`) end to end: compiled from a profile, read off a
 * deserialized resource, and reported the way the HL7 reference validator reports them.
 *
 * Each shape is one that `fhir-test-cases` 1.7.49 exercises and that was missed before #161:
 *  - a fixed complex value, which the instance must equal exactly, so an element the fixed value
 *    does not carry is a violation (R5 `observation-cholesterol-bad-*`);
 *  - a fixed value on an element inside a slice (R4 `obs-de`, KBV `code.coding:loinc.version`);
 *  - an extension URL with a `|version` suffix against the definition's fixed `Extension.url`
 *    (R4 `versioned-extension`).
 */
#[CoversClass(FHIRFixedValueValidator::class)]
#[CoversClass(FHIRValidationService::class)]
final class FixedValueValidationTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string FIXED_CODE = 'http://example.org/fhir/StructureDefinition/fixed-code';

    private const string FIXED_HIGH = 'http://example.org/fhir/StructureDefinition/fixed-high';

    private const string SLICED_VERSION = 'http://example.org/fhir/StructureDefinition/sliced-version';

    private const string DISPLAY = 'Cholesterol [Moles/volume] in Serum or Plasma';

    private FHIRValidationService $service;

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $registry = new InMemoryStructureDefinitionRegistry([
            self::specialization('Observation'),
            // The cholesterol profile's two complex fixed values, trimmed to what the compiler reads
            self::profile(self::FIXED_CODE, [[
                'id'                   => 'Observation.code',
                'path'                 => 'Observation.code',
                'fixedCodeableConcept' => ['coding' => [['system' => 'http://loinc.org', 'code' => '35200-5', 'display' => self::DISPLAY]]],
            ]]),
            self::profile(self::FIXED_HIGH, [
                ['id' => 'Observation.referenceRange', 'path' => 'Observation.referenceRange', 'min' => 1, 'max' => '1'],
                ['id' => 'Observation.referenceRange.high', 'path' => 'Observation.referenceRange.high', 'fixedQuantity' => ['value' => 4.5]],
            ]),
            // The KBV body weight profile's LOINC slice, whose version is fixed. KBV discriminates by
            // pattern on $this; a value discriminator on system keeps this test about the fixed value.
            self::profile(self::SLICED_VERSION, [
                [
                    'id'      => 'Observation.code.coding',
                    'path'    => 'Observation.code.coding',
                    'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'system']], 'rules' => 'open'],
                ],
                ['id' => 'Observation.code.coding:loinc', 'path' => 'Observation.code.coding', 'sliceName' => 'loinc'],
                ['id' => 'Observation.code.coding:loinc.system', 'path' => 'Observation.code.coding.system', 'fixedUri' => 'http://loinc.org'],
                ['id' => 'Observation.code.coding:loinc.version', 'path' => 'Observation.code.coding.version', 'fixedString' => '2.71'],
            ]),
        ]);

        $this->service    = OracleValidationServiceFactory::create(FhirVersion::R4, false, new RuntimeProfileResolver($registry));
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testComplexValueEqualToTheFixedValueHasNoFindings(): void
    {
        self::assertSame([], $this->profileFindings(['code' => self::fixedCode()], [self::FIXED_CODE]));
    }

    public function testElementTheFixedValueDoesNotCarryIsReported(): void
    {
        self::assertSame(
            [[self::FIXED_CODE, 'code.text', 'The element text is present in the instance but not allowed in the applicable fixed value specified in profile']],
            $this->profileFindings(['code' => [...self::fixedCode(), 'text' => 'Cholesterol']], [self::FIXED_CODE]),
        );
    }

    public function testEachDifferingValueInsideTheFixedValueIsReported(): void
    {
        $code                         = self::fixedCode();
        $code['coding'][0]['code']    = '13457-7';
        $code['coding'][0]['display'] = 'Cholesterol in LDL';

        self::assertSame(
            [
                [self::FIXED_CODE, 'code.coding[0].code', "Value is '13457-7' but is fixed to '35200-5'"],
                [self::FIXED_CODE, 'code.coding[0].display', "Value is 'Cholesterol in LDL' but is fixed to '" . self::DISPLAY . "'"],
            ],
            $this->profileFindings(['code' => $code], [self::FIXED_CODE]),
        );
    }

    public function testFixedQuantityRejectsAWrongValueAndEveryExtraElement(): void
    {
        $high = ['value' => 0, 'unit' => 'mmol/L', 'system' => 'http://unitsofmeasure.org', 'code' => 'mmol/L'];

        self::assertSame(
            [
                [self::FIXED_HIGH, 'referenceRange.high.value', "Value is '0' but is fixed to '4.5'"],
                [self::FIXED_HIGH, 'referenceRange.high.unit', 'The element unit is present in the instance but not allowed in the applicable fixed value specified in profile'],
                [self::FIXED_HIGH, 'referenceRange.high.system', 'The element system is present in the instance but not allowed in the applicable fixed value specified in profile'],
                [self::FIXED_HIGH, 'referenceRange.high.code', 'The element code is present in the instance but not allowed in the applicable fixed value specified in profile'],
            ],
            $this->profileFindings(['referenceRange' => [['high' => $high]]], [self::FIXED_HIGH]),
        );
    }

    public function testFixedDecimalComparesByValueNotByLexicalForm(): void
    {
        // 4.50 written out by hand: json_encode() would print it as 4.5 and prove nothing
        $resource = $this->serializer->deserialize(
            '{"resourceType": "Observation", "status": "final", "code": {"text": "x"}, "referenceRange": [{"high": {"value": 4.50}}]}',
        );

        self::assertSame([], $this->findingsFor($resource, [self::FIXED_HIGH]));
    }

    public function testFixedValueInsideASliceIsChecked(): void
    {
        $code = ['coding' => [['system' => 'http://loinc.org', 'version' => '2.81', 'code' => '29463-7']]];

        self::assertSame(
            [[self::SLICED_VERSION, 'code.coding.version', "Value is '2.81' but is fixed to '2.71'"]],
            array_map(
                static fn (array $finding): array => [$finding[0], (string) preg_replace('/\[\d+\]/', '', $finding[1]), $finding[2]],
                $this->profileFindings(['code' => $code], [self::SLICED_VERSION]),
            ),
        );
    }

    public function testVersionedExtensionUrlBreaksTheDefinitionsFixedUrl(): void
    {
        $service = OracleValidationServiceFactory::create(
            FhirVersion::R4,
            true,
            new RuntimeProfileResolver(new InMemoryStructureDefinitionRegistry([])),
        );
        $patient = $this->serializer->deserialize((string) json_encode([
            'resourceType' => 'Patient',
            'extension'    => [
                ['url' => self::CORE . 'patient-congregation|4.0.0', 'valueString' => 'Just the local temple'],
                // Unknown to every registry: a version suffix on it says nothing about a fixed url
                ['url' => 'http://example.org/fhir/StructureDefinition/unknown|1.0', 'valueString' => 'x'],
            ],
        ]));

        $findings = array_values(array_filter(
            $service->validateWithOptions($patient, new FHIRValidationOptions())->violations,
            static fn (FHIRValidationViolation $v): bool => str_contains($v->message, 'is fixed to'),
        ));

        self::assertSame(
            [['extension[0].url', "Value is '" . self::CORE . "patient-congregation|4.0.0' but is fixed to '" . self::CORE . "patient-congregation'"]],
            array_map(static fn (FHIRValidationViolation $v): array => [$v->path, $v->message], $findings),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private static function fixedCode(): array
    {
        return ['coding' => [['system' => 'http://loinc.org', 'code' => '35200-5', 'display' => self::DISPLAY]]];
    }

    /**
     * Profile findings as [profile, path, message], the base resource's own findings left out.
     *
     * @param array<string, mixed> $observation fields added to a minimal valid Observation
     * @param list<string>         $profileUrls
     *
     * @return list<array{0: string, 1: string, 2: string}>
     */
    private function profileFindings(array $observation, array $profileUrls): array
    {
        $resource = $this->serializer->deserialize((string) json_encode(
            ['resourceType' => 'Observation', 'status' => 'final', 'code' => ['text' => 'x'], ...$observation],
        ));

        return $this->findingsFor($resource, $profileUrls);
    }

    /**
     * @param list<string> $profileUrls
     *
     * @return list<array{0: string, 1: string, 2: string}>
     */
    private function findingsFor(object $resource, array $profileUrls): array
    {
        $report = $this->service->validateWithOptions($resource, new FHIRValidationOptions(profileUrls: $profileUrls));

        return array_values(array_map(
            static fn (FHIRValidationViolation $v): array => [(string) $v->profileGroup, $v->path, $v->message],
            array_filter($report->violations, static fn (FHIRValidationViolation $v): bool => $v->profileGroup !== null),
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private static function specialization(string $type): array
    {
        return [
            'resourceType' => 'StructureDefinition',
            'url'          => self::CORE . $type,
            'fhirVersion'  => '4.0.1',
            'kind'         => 'resource',
            'type'         => $type,
            'derivation'   => 'specialization',
            'snapshot'     => ['element' => [['id' => $type, 'path' => $type]]],
        ];
    }

    /**
     * An Observation constraint profile whose differential and snapshot both hold $elements.
     *
     * @param list<array<string, mixed>> $elements
     *
     * @return array<string, mixed>
     */
    private static function profile(string $url, array $elements): array
    {
        $elements = [['id' => 'Observation', 'path' => 'Observation'], ...$elements];

        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'version'        => '1.0',
            'fhirVersion'    => '4.0.1',
            'kind'           => 'resource',
            'type'           => 'Observation',
            'baseDefinition' => self::CORE . 'Observation',
            'derivation'     => 'constraint',
            'snapshot'       => ['element' => $elements],
            'differential'   => ['element' => $elements],
        ];
    }
}

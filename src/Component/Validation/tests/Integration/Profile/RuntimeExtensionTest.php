<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\ComplexExtensionChecker;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * A plain Extension whose url names a definition loaded at runtime is held to that definition:
 * where it may appear, what value it holds, and which sub-extensions it needs.
 */
#[CoversClass(FHIRValidationService::class)]
#[CoversClass(ComplexExtensionChecker::class)]
#[CoversClass(RuntimeProfileResolver::class)]
final class RuntimeExtensionTest extends TestCase
{
    private const string BASE = 'http://example.org/fhir/StructureDefinition/';

    private const string ON_OBSERVATION = self::BASE . 'observation-only';

    private const string NICKNAME = self::BASE . 'nickname';

    private const string PET = self::BASE . 'pet';

    private const string FLAG = self::BASE . 'flag';

    private FHIRValidationService $service;

    protected function setUp(): void
    {
        $registry = new InMemoryStructureDefinitionRegistry([
            self::extension(self::ON_OBSERVATION, [['type' => 'element', 'expression' => 'Observation']], [
                ['id' => 'Extension.value[x]', 'path' => 'Extension.value[x]', 'min' => 0, 'max' => '1', 'type' => [['code' => 'string']]],
            ]),
            self::extension(self::NICKNAME, [['type' => 'element', 'expression' => 'Patient']], [
                ['id' => 'Extension.value[x]', 'path' => 'Extension.value[x]', 'min' => 0, 'max' => '1', 'type' => [['code' => 'string']]],
            ]),
            self::extension(self::PET, [['type' => 'element', 'expression' => 'Patient']], [
                ['id' => 'Extension.extension', 'path' => 'Extension.extension', 'min' => 0, 'max' => '*'],
                ['id' => 'Extension.extension:species', 'path' => 'Extension.extension', 'sliceName' => 'species', 'min' => 1, 'max' => '1'],
                ['id' => 'Extension.extension:species.value[x]', 'path' => 'Extension.extension.value[x]', 'min' => 1, 'max' => '1', 'type' => [['code' => 'CodeableConcept']]],
                ['id' => 'Extension.extension:name', 'path' => 'Extension.extension', 'sliceName' => 'name', 'min' => 0, 'max' => '1'],
                ['id' => 'Extension.extension:value', 'path' => 'Extension.extension', 'sliceName' => 'value', 'min' => 0, 'max' => '1'],
                ['id' => 'Extension.extension:name.value[x]', 'path' => 'Extension.extension.value[x]', 'min' => 1, 'max' => '1', 'type' => [['code' => 'string']]],
                ['id' => 'Extension.value[x]', 'path' => 'Extension.value[x]', 'min' => 0, 'max' => '0'],
            ]),
            self::extension(self::FLAG, [['type' => 'element', 'expression' => 'Patient']], [
                ['id' => 'Extension.value[x]', 'path' => 'Extension.value[x]', 'min' => 0, 'max' => '1', 'type' => [['code' => 'boolean']]],
            ]),
        ]);

        $this->service = OracleValidationServiceFactory::create(FhirVersion::R4, true, new RuntimeProfileResolver($registry));
    }

    public function testAnExtensionOutsideItsContextIsReported(): void
    {
        $patient            = new PatientResource();
        $patient->extension = [new Extension(url: self::ON_OBSERVATION, value: new StringPrimitive(value: 'x'))];

        self::assertSame(
            [['extension', 'Extension "' . self::ON_OBSERVATION . '" is not permitted on element "Patient".']],
            $this->findings($patient, 'not permitted'),
        );

        $patient->extension = [new Extension(url: self::NICKNAME, value: new StringPrimitive(value: 'x'))];
        self::assertSame([], $this->findings($patient, 'not permitted'));
    }

    public function testAValueOfAnotherTypeIsReported(): void
    {
        $patient            = new PatientResource();
        $patient->extension = [new Extension(url: self::NICKNAME, value: new Coding())];

        self::assertSame(
            [['extension', "The Extension '" . self::NICKNAME . "' definition allows for the types [string] but found type Coding"]],
            $this->findings($patient, 'definition allows'),
        );
    }

    public function testAMissingRequiredSubExtensionIsReported(): void
    {
        $patient            = new PatientResource();
        $patient->extension = [new Extension(url: self::PET, extension: [new Extension(url: 'name', value: new StringPrimitive(value: 'Rex'))])];

        self::assertSame(
            [[
                'extension[0]',
                "Slice 'Extension.extension:species': a matching slice is required, but not found (from " . self::PET . '). '
                . 'Note that other slices are allowed in addition to this required slice',
            ]],
            $this->findings($patient, 'Extension.extension:'),
        );
    }

    /**
     * A required slice whose value has another type does not fill it, as in the generated class.
     */
    public function testARequiredSubExtensionOfTheWrongTypeIsMissing(): void
    {
        $patient            = new PatientResource();
        $patient->extension = [new Extension(url: self::PET, extension: [new Extension(url: 'species', value: new StringPrimitive(value: 'dog'))])];

        self::assertCount(1, $this->findings($patient, 'Extension.extension:species'));
    }

    public function testAnUndeclaredSubExtensionIsReported(): void
    {
        $patient            = new PatientResource();
        $patient->extension = [new Extension(url: self::PET, extension: [
            new Extension(url: 'species', value: new CodeableConcept()),
            new Extension(url: 'colour', value: new StringPrimitive(value: 'brown')),
            new Extension(url: 'http://example.org/other', value: new StringPrimitive(value: 'open slicing')),
        ])];

        self::assertSame(
            [['extension[0].extension[1]', "Sub-extension url 'colour' is not defined by the Extension " . self::PET]],
            $this->findings($patient, 'Sub-extension url'),
        );
        self::assertSame([], $this->findings($patient, 'Extension.extension:'));
    }

    /**
     * The generated class names a slice called `value` `$valueSlice`, and treats both urls as declared.
     */
    public function testAReservedSliceNameIsDeclaredUnderItsParameterNameToo(): void
    {
        $patient            = new PatientResource();
        $patient->extension = [new Extension(url: self::PET, extension: [
            new Extension(url: 'species', value: new CodeableConcept()),
            new Extension(url: 'value', value: new StringPrimitive(value: 'a')),
            new Extension(url: 'valueSlice', value: new StringPrimitive(value: 'b')),
            new Extension(url: 'id', value: new StringPrimitive(value: 'c')),
        ])];

        self::assertSame([], $this->findings($patient, 'Sub-extension url'));
    }

    public function testAKnownModifierExtensionIsNotUnknown(): void
    {
        $patient                    = new PatientResource();
        $patient->modifierExtension = [
            new Extension(url: self::FLAG, value: true),
            new Extension(url: self::BASE . 'never-loaded', value: true),
        ];

        self::assertSame(
            [['modifierExtension', 'Unknown modifier extension: ' . self::BASE . 'never-loaded — resource cannot be safely processed']],
            $this->findings($patient, 'Unknown modifier extension'),
        );
    }

    public function testAnExtensionWithNoLoadedDefinitionIsLeftAlone(): void
    {
        $patient            = new PatientResource();
        $patient->extension = [new Extension(url: self::BASE . 'never-loaded', value: new Coding())];

        self::assertSame([], $this->findings($patient, 'Extension'));
    }

    /**
     * Path and message of each finding whose message contains $needle.
     *
     * @return list<array{string, string}>
     */
    private function findings(PatientResource $patient, string $needle): array
    {
        $found = [];
        foreach ($this->service->validate($patient)->violations as $violation) {
            if (str_contains($violation->message, $needle)) {
                $found[] = [$violation->path, $violation->message];
            }
        }

        return $found;
    }

    /**
     * @param list<array{type: string, expression: string}> $contexts
     * @param list<array<string, mixed>>                    $elements
     *
     * @return array<string, mixed>
     */
    private static function extension(string $url, array $contexts, array $elements): array
    {
        $elements = [
            ['id' => 'Extension', 'path' => 'Extension', 'min' => 0, 'max' => '*'],
            ...$elements,
            ['id' => 'Extension.url', 'path' => 'Extension.url', 'min' => 1, 'max' => '1', 'fixedUri' => $url],
        ];

        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'fhirVersion'    => '4.0.1',
            'kind'           => 'complex-type',
            'type'           => 'Extension',
            'context'        => $contexts,
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Extension',
            'derivation'     => 'constraint',
            'snapshot'       => ['element' => $elements],
            'differential'   => ['element' => $elements],
        ];
    }
}

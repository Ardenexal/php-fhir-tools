<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4\Extension\DoNotPerformExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\TaskResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRVersionRange;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * An extension is held to where its definition says it may be used: a modifier extension only in
 * `modifierExtension`, a regular one only in `extension`, and either only in the FHIR versions its
 * `version-specific-use` allows, overall or per context. Generated classes and definitions loaded at
 * runtime are read the same way.
 */
#[CoversClass(FHIRValidationService::class)]
#[CoversClass(FHIRVersionRange::class)]
final class ExtensionModifierAndVersionTest extends TestCase
{
    private const string BASE = 'http://example.org/fhir/StructureDefinition/';

    private const string MODIFIER = self::BASE . 'modifier';

    private const string REGULAR = self::BASE . 'regular';

    private const string FROM_R4B = self::BASE . 'from-r4b';

    private const string UP_TO_R4 = self::BASE . 'up-to-r4';

    private const string PATIENT_UNTIL_STU3 = self::BASE . 'patient-until-stu3';

    private const string CODE_SYSTEM_PROPERTY = self::BASE . 'code-system-property';

    private const string CANONICAL = self::BASE . 'canonical';

    private const string ON_PATIENT_NAME = self::BASE . 'on-patient-name';

    private FHIRValidationService $service;

    protected function setUp(): void
    {
        $patient = [['type' => 'element', 'expression' => 'Patient']];

        $this->service = OracleValidationServiceFactory::create(FhirVersion::R4, true, new RuntimeProfileResolver(new InMemoryStructureDefinitionRegistry([
            self::extension(self::MODIFIER, $patient, isModifier: true),
            self::extension(self::REGULAR, $patient),
            self::extension(self::FROM_R4B, $patient, range: ['startFhirVersion' => '4.3']),
            self::extension(self::UP_TO_R4, $patient, range: ['endFhirVersion' => '4.0']),
            self::extension(self::PATIENT_UNTIL_STU3, [
                ['type' => 'element', 'expression' => 'Patient', 'extension' => [self::versionSpecificUse(['endFhirVersion' => '3.0'])]],
                ['type' => 'element', 'expression' => 'Observation'],
            ]),
            self::extension(self::CODE_SYSTEM_PROPERTY, [['type' => 'element', 'expression' => 'CodeSystem.property']]),
            self::extension(self::CANONICAL, [['type' => 'element', 'expression' => 'CanonicalResource']]),
            self::extension(self::ON_PATIENT_NAME, [['type' => 'element', 'expression' => 'Patient.name']]),
        ])));
    }

    public function testAModifierExtensionInExtensionIsReported(): void
    {
        $patient            = new PatientResource();
        $patient->extension = [self::plain(self::MODIFIER)];

        self::assertSame(
            [['extension[0]', "The Extension '" . self::MODIFIER . "' must not be used as an extension (it's a modifierExtension)"]],
            $this->findings($patient, 'must not be used'),
        );

        $patient->extension         = [];
        $patient->modifierExtension = [self::plain(self::MODIFIER)];
        self::assertSame([], $this->findings($patient, 'must not be used'));
    }

    public function testARegularExtensionInModifierExtensionIsReported(): void
    {
        $patient                    = new PatientResource();
        $patient->modifierExtension = [self::plain(self::REGULAR)];

        self::assertSame(
            [['modifierExtension[0]', "The Extension '" . self::REGULAR . "' must not be used as a modifierExtension (it's a regular extension)"]],
            $this->findings($patient, 'must not be used'),
        );
    }

    /**
     * A generated modifier extension class carries `isModifier` on its `#[FHIRExtensionDefinition]`.
     */
    public function testAGeneratedModifierExtensionInExtensionIsReported(): void
    {
        $task            = new TaskResource();
        $task->extension = [new DoNotPerformExtension(valueBoolean: true)];

        self::assertSame(
            [['extension[0]', "The Extension 'http://hl7.org/fhir/StructureDefinition/request-doNotPerform' must not be used as an extension (it's a modifierExtension)"]],
            $this->findings($task, 'must not be used'),
        );

        $task->extension         = [];
        $task->modifierExtension = [new DoNotPerformExtension(valueBoolean: true)];
        self::assertSame([], $this->findings($task, 'must not be used'));
    }

    /**
     * A class generated before 0.8 does not say whether it is a modifier, so it is not reported in
     * either place.
     */
    public function testAnExtensionClassThatDoesNotSayIsNotReported(): void
    {
        $task                    = new TaskResource();
        $task->modifierExtension = [new #[FHIRExtensionDefinition(url: 'http://hl7.org/fhir/StructureDefinition/request-doNotPerform', fhirVersion: 'R4')] class extends Extension {
            public function __construct()
            {
                parent::__construct(url: 'http://hl7.org/fhir/StructureDefinition/request-doNotPerform');
            }
        }];

        self::assertSame([], $this->findings($task, 'must not be used'));
    }

    public function testAnExtensionUsedOutsideItsVersionRangeIsReported(): void
    {
        $patient            = new PatientResource();
        $patient->extension = [self::plain(self::FROM_R4B)];

        self::assertSame(
            [['extension', "The definition of the extension '" . self::FROM_R4B . "' specifies that the earliest version it can be used with is R4B (v4.3), but this context of use is version R4 (v4.0.1)"]],
            $this->findings($patient, 'version it can be used with'),
        );

        $patient->extension = [self::plain(self::UP_TO_R4)];
        self::assertSame([], $this->findings($patient, ''));
    }

    /**
     * A context limited to other FHIR versions does not apply. Here the extension's only context left
     * for R4 is Observation, so on Patient it is not permitted.
     */
    public function testAContextOutsideItsVersionRangeDoesNotApply(): void
    {
        $patient            = new PatientResource();
        $patient->extension = [self::plain(self::PATIENT_UNTIL_STU3)];

        self::assertSame(
            [['extension', 'Extension "' . self::PATIENT_UNTIL_STU3 . '" is not permitted on element "Patient".']],
            $this->findings($patient, 'not permitted'),
        );
    }

    /**
     * The resource being validated sits at no element path, so a context naming an element inside
     * another resource cannot permit an extension on it. CanonicalResource is a pattern in R4, which
     * no R4 type derives from, so it is left undecided rather than denied.
     */
    public function testAtTheRootAContextInsideAnotherResourceIsDeniedButAPatternIsNot(): void
    {
        $patient            = new PatientResource();
        $patient->extension = [self::plain(self::CODE_SYSTEM_PROPERTY)];

        self::assertSame(
            [['extension', 'Extension "' . self::CODE_SYSTEM_PROPERTY . '" is not permitted on element "Patient".']],
            $this->findings($patient, 'not permitted'),
        );

        $patient->extension = [self::plain(self::CANONICAL)];
        self::assertSame([], $this->findings($patient, 'not permitted'));
    }

    /**
     * A data type validated on its own may be meant for an element path, so a dotted context is
     * left undecided rather than denied.
     */
    public function testADataTypeValidatedOnItsOwnLeavesADottedContextUndecided(): void
    {
        $name            = new HumanName(family: new StringPrimitive(value: 'Chalmers'));
        $name->extension = [self::plain(self::ON_PATIENT_NAME)];

        self::assertSame([], $this->findings($name, 'not permitted'));
    }

    private static function plain(string $url): Extension
    {
        return new Extension(url: $url, value: new StringPrimitive(value: 'x'));
    }

    /**
     * @return list<array{0: string, 1: string}> path and message of each finding whose message holds $needle
     */
    private function findings(object $resource, string $needle): array
    {
        $found = [];
        foreach ($this->service->validate($resource)->violations as $violation) {
            if ($violation->severity === 'error' && str_contains($violation->message, $needle)) {
                $found[] = [$violation->path, $violation->message];
            }
        }

        return $found;
    }

    /**
     * @param list<array<string, mixed>> $contexts
     * @param array<string, string>      $range
     *
     * @return array<string, mixed>
     */
    private static function extension(string $url, array $contexts, bool $isModifier = false, array $range = []): array
    {
        $elements = [
            ['id' => 'Extension', 'path' => 'Extension', 'min' => 0, 'max' => '*', 'isModifier' => $isModifier],
            ['id' => 'Extension.value[x]', 'path' => 'Extension.value[x]', 'min' => 0, 'max' => '1', 'type' => [['code' => 'string']]],
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
            ...($range === [] ? [] : ['extension' => [self::versionSpecificUse($range)]]),
        ];
    }

    /**
     * @param array<string, string> $bounds
     *
     * @return array<string, mixed>
     */
    private static function versionSpecificUse(array $bounds): array
    {
        $extension = [];
        foreach ($bounds as $name => $version) {
            $extension[] = ['url' => $name, 'valueCode' => $version];
        }

        return ['url' => 'http://hl7.org/fhir/StructureDefinition/version-specific-use', 'extension' => $extension];
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileBinding;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\FHIRViolationCode;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileBindingValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRProfileInvariantValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * A profile's invariants and bindings, loaded at runtime, report at the element they are about.
 *
 * The harness has no terminology client, so a binding no generated enum can check is reported as
 * unchecked. Any value set with a generated enum will do for a bad code; administrative-gender is
 * used for that, bound to an element it does not normally describe.
 */
#[CoversClass(FHIRProfileInvariantValidator::class)]
#[CoversClass(FHIRProfileBindingValidator::class)]
#[CoversClass(FHIRValidationService::class)]
final class RuntimeProfileInvariantAndBindingTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string PROFILE = 'http://example.org/fhir/StructureDefinition/checked-observation';

    private const string GENDER = 'http://hl7.org/fhir/ValueSet/administrative-gender';

    private FHIRValidationService $service;

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $elements = [
            ['id' => 'Observation', 'path' => 'Observation', 'constraint' => [self::invariant('chk-1', 'error', "status = 'final'", 'The observation is final')]],
            ['id' => 'Observation.code', 'path' => 'Observation.code', 'binding' => ['strength' => 'required', 'valueSet' => self::GENDER]],
            ['id' => 'Observation.method', 'path' => 'Observation.method', 'binding' => ['strength' => 'extensible', 'valueSet' => 'http://example.org/ValueSet/methods']],
            ['id' => 'Observation.bodySite', 'path' => 'Observation.bodySite', 'binding' => ['strength' => 'required', 'valueSet' => 'http://example.org/ValueSet/no-enum']],
            ['id' => 'Observation.component', 'path' => 'Observation.component', 'constraint' => [
                self::invariant('chk-2', 'error', 'code.coding.exists()', 'A component is coded'),
                self::invariant('chk-3', 'warning', 'value.exists() or dataAbsentReason.exists()', 'A component has a value'),
                self::invariant('chk-4', 'error', '%resource.subject.exists()', 'The observation around a component has a subject'),
            ]],
        ];

        $registry = new InMemoryStructureDefinitionRegistry([
            [
                'resourceType' => 'StructureDefinition',
                'url'          => self::CORE . 'Observation',
                'fhirVersion'  => '4.0.1',
                'kind'         => 'resource',
                'type'         => 'Observation',
                'derivation'   => 'specialization',
                'snapshot'     => ['element' => [['id' => 'Observation', 'path' => 'Observation']]],
            ],
            [
                'resourceType'   => 'StructureDefinition',
                'url'            => self::PROFILE,
                'fhirVersion'    => '4.0.1',
                'kind'           => 'resource',
                'type'           => 'Observation',
                'baseDefinition' => self::CORE . 'Observation',
                'derivation'     => 'constraint',
                'snapshot'       => ['element' => $elements],
                'differential'   => ['element' => $elements],
            ],
        ]);

        $this->service    = OracleValidationServiceFactory::create(FhirVersion::R4, false, new RuntimeProfileResolver($registry));
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testAConformingObservationHasNoFindings(): void
    {
        self::assertSame([], $this->findings([
            'subject'   => ['reference' => 'Patient/1'],
            'component' => [self::component('a', ['valueString' => 'x'])],
        ]));
    }

    public function testEachInvariantReportsAtTheOccurrenceItIsAbout(): void
    {
        $findings = $this->findings([
            'status'    => 'amended',
            'subject'   => ['reference' => 'Patient/1'],
            'component' => [self::component('a', ['valueString' => 'x']), ['code' => ['text' => 'uncoded']]],
        ]);

        // Two invariants failing on one element are both reported, on the second component only
        self::assertSame([
            ['error', '', FHIRViolationCode::ERROR, 'The observation is final', FHIRProfileInvariant::class, self::PROFILE, 'chk-1'],
            ['error', 'component[1]', FHIRViolationCode::ERROR, 'A component is coded', FHIRProfileInvariant::class, self::PROFILE, 'chk-2'],
            ['warning', 'component[1]', FHIRViolationCode::WARNING, 'A component has a value', FHIRProfileInvariant::class, self::PROFILE, 'chk-3'],
        ], $findings);
    }

    /**
     * `%resource` is the observation around the component, not the component itself.
     */
    public function testResourceIsTheResourceAroundTheElement(): void
    {
        $component = [self::component('a', ['valueString' => 'x'])];

        self::assertSame([], $this->findings(['subject' => ['reference' => 'Patient/1'], 'component' => $component]));
        self::assertSame(
            [['error', 'component[0]', FHIRViolationCode::ERROR, 'The observation around a component has a subject', FHIRProfileInvariant::class, self::PROFILE, 'chk-4']],
            $this->findings(['component' => $component]),
        );
    }

    public function testACodeOutsideARequiredValueSetIsReported(): void
    {
        $findings = $this->findings([
            'subject' => ['reference' => 'Patient/1'],
            'code'    => ['coding' => [['system' => 'http://hl7.org/fhir/administrative-gender', 'code' => 'unicorn']]],
        ]);

        self::assertSame(
            [['error', 'code', FHIRViolationCode::ERROR, 'The value unicorn is not a valid case of value set ' . self::GENDER . '.', FHIRProfileBinding::class, self::PROFILE, '']],
            $findings,
        );
    }

    /**
     * With no terminology client, an extensible binding, and a required one no generated enum covers,
     * are each reported as unchecked rather than passed or failed.
     */
    public function testABindingNothingCanCheckIsReportedAsUnchecked(): void
    {
        $findings = $this->findings([
            'subject'  => ['reference' => 'Patient/1'],
            'method'   => ['coding' => [['system' => 'http://example.org', 'code' => 'm']]],
            'bodySite' => ['coding' => [['system' => 'http://example.org', 'code' => 's']]],
        ]);

        self::assertSame([
            ['info', 'bodySite', FHIRViolationCode::UNCHECKED_BINDING, 'Terminology validation for value set http://example.org/ValueSet/no-enum was skipped: no terminology client is configured.', FHIRProfileBinding::class, self::PROFILE, ''],
            ['info', 'method', FHIRViolationCode::UNCHECKED_BINDING, 'Terminology validation for value set http://example.org/ValueSet/methods was skipped: no terminology client is configured.', FHIRProfileBinding::class, self::PROFILE, ''],
        ], $findings);
    }

    /**
     * The findings in the profile's group, sorted, as severity, path, code, message, constraint, group and key.
     *
     * @param array<string, mixed> $extra
     *
     * @return list<array{string, string, string|null, string, string, string, string}>
     */
    private function findings(array $extra): array
    {
        $json        = (string) json_encode(['resourceType' => 'Observation', 'status' => 'final', 'code' => ['text' => 'x'], ...$extra]);
        $observation = $this->serializer->deserialize($json);

        $found = array_map(
            static fn (FHIRValidationViolation $v): array => [$v->severity, $v->path, $v->code, $v->message, $v->constraintClass, (string) $v->profileGroup, (string) $v->invariantKey],
            array_values(array_filter(
                $this->service->validate($observation, [self::PROFILE])->violations,
                static fn (FHIRValidationViolation $v): bool => $v->profileGroup === self::PROFILE,
            )),
        );
        usort($found, static fn (array $a, array $b): int => [$a[1], $a[6]] <=> [$b[1], $b[6]]);

        return $found;
    }

    /**
     * @return array<string, string>
     */
    private static function invariant(string $key, string $severity, string $expression, string $human): array
    {
        return ['key' => $key, 'severity' => $severity, 'expression' => $expression, 'human' => $human];
    }

    /**
     * @param array<string, mixed> $value
     *
     * @return array<string, mixed>
     */
    private static function component(string $code, array $value): array
    {
        return ['code' => ['coding' => [['system' => 'http://example.org', 'code' => $code]]], ...$value];
    }
}

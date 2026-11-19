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
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Invariants and bindings a profile places on a slice, or beneath one, hold for the items in that
 * slice only, and report at the item they are about.
 *
 * The profile slices Observation.component by its code. The `systolic` slice carries an invariant
 * on itself, one using `%resource`, and a binding on `value[x]` that administrative-gender's
 * generated enum can check without a terminology client.
 */
#[CoversClass(FHIRSliceConstraintValidator::class)]
final class RuntimeSliceInvariantAndBindingTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string PROFILE = 'http://example.org/fhir/StructureDefinition/sliced-observation';

    private const string GENDER = 'http://hl7.org/fhir/ValueSet/administrative-gender';

    private FHIRValidationService $service;

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $elements = [
            ['id' => 'Observation', 'path' => 'Observation'],
            ['id' => 'Observation.component', 'path' => 'Observation.component', 'slicing' => [
                'discriminator' => [['type' => 'pattern', 'path' => 'code']],
                'rules'         => 'open',
            ]],
            ['id' => 'Observation.component:systolic', 'path' => 'Observation.component', 'sliceName' => 'systolic', 'min' => 0, 'max' => '1', 'constraint' => [
                self::invariant('sys-1', 'value.exists()', 'A systolic component has a value'),
                self::invariant('sys-2', '%resource.subject.exists()', 'The observation around a systolic component has a subject'),
            ]],
            ['id'                        => 'Observation.component:systolic.code', 'path' => 'Observation.component.code',
                'patternCodeableConcept' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8480-6']]],
            ],
            ['id'         => 'Observation.component:systolic.value[x]', 'path' => 'Observation.component.value[x]',
                'binding' => ['strength' => 'required', 'valueSet' => self::GENDER],
            ],
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

    public function testAConformingSliceItemHasNoFindings(): void
    {
        self::assertSame([], $this->findings([
            'subject'   => ['reference' => 'Patient/1'],
            'component' => [self::systolic(self::gender('male'))],
        ]));
    }

    /**
     * The slice's rules apply to the item in the slice, not to the other component beside it.
     */
    public function testASliceInvariantReportsAtTheItemInTheSlice(): void
    {
        $findings = $this->findings([
            'subject'   => ['reference' => 'Patient/1'],
            'component' => [['code' => ['text' => 'other']], self::systolic([])],
        ]);

        self::assertSame(
            [['error', 'component[1]', FHIRViolationCode::ERROR, 'A systolic component has a value', FHIRProfileInvariant::class, self::PROFILE, 'sys-1']],
            $findings,
        );
    }

    /**
     * `%resource` in a slice rule is the observation around the item, not the item itself.
     */
    public function testResourceInASliceRuleIsTheResourceAroundTheItem(): void
    {
        self::assertSame(
            [['error', 'component[0]', FHIRViolationCode::ERROR, 'The observation around a systolic component has a subject', FHIRProfileInvariant::class, self::PROFILE, 'sys-2']],
            $this->findings(['component' => [self::systolic(self::gender('male'))]]),
        );
    }

    public function testASliceBindingReportsAtTheItemsElement(): void
    {
        $findings = $this->findings([
            'subject'   => ['reference' => 'Patient/1'],
            'component' => [['code' => ['text' => 'other'], ...self::gender('unicorn')], self::systolic(self::gender('unicorn'))],
        ]);

        // Only the item in the slice is bound; the other component may hold any code
        self::assertSame(
            [['error', 'component[1].value', FHIRViolationCode::ERROR, 'The value unicorn is not a valid case of value set ' . self::GENDER . '.', FHIRProfileBinding::class, self::PROFILE, '']],
            $findings,
        );
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
    private static function invariant(string $key, string $expression, string $human): array
    {
        return ['key' => $key, 'severity' => 'error', 'expression' => $expression, 'human' => $human];
    }

    /**
     * A component in the systolic slice: its code carries the slice's LOINC coding.
     *
     * @param array<string, mixed> $value
     *
     * @return array<string, mixed>
     */
    private static function systolic(array $value): array
    {
        return ['code' => ['coding' => [['system' => 'http://loinc.org', 'code' => '8480-6']]], ...$value];
    }

    /**
     * @return array<string, mixed>
     */
    private static function gender(string $code): array
    {
        return ['valueCodeableConcept' => ['coding' => [['system' => 'http://hl7.org/fhir/administrative-gender', 'code' => $code]]]];
    }
}

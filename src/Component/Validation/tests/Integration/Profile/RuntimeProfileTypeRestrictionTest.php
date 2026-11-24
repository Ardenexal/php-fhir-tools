<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRAllowedTypes;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Profile\StructureDefinitionCompiler;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationOptions;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRAllowedTypesValidator;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRTargetProfileValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Type and target-profile restrictions in runtime profiles (#229), inside a slice and at the top level.
 *
 * The sliced profile is the one from the issue: `Parameters.parameter` sliced closed on `name`, with
 * a `requisition` slice whose value must be a string and a `copyTo` slice whose value must be a
 * Reference to a PractitionerRole.
 */
#[CoversClass(FHIRAllowedTypesValidator::class)]
#[CoversClass(FHIRTargetProfileValidator::class)]
final class RuntimeProfileTypeRestrictionTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string SUBMIT = 'http://example.org/fhir/StructureDefinition/submit-parameters';

    private const string QUANTITY_OBSERVATION = 'http://example.org/fhir/StructureDefinition/quantity-observation';

    private const string IG_PATIENT = 'http://example.org/fhir/StructureDefinition/ig-patient';

    private const string IG_SUBJECT_OBSERVATION = 'http://example.org/fhir/StructureDefinition/ig-subject-observation';

    private const string PATIENT_BUNDLE = 'http://example.org/fhir/StructureDefinition/patient-bundle';

    private FHIRValidationService $service;

    private FHIRSerializationService $serializer;

    private RuntimeProfileResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new RuntimeProfileResolver(new InMemoryStructureDefinitionRegistry([
            self::specialization('Parameters'),
            self::specialization('Observation'),
            self::specialization('Patient'),
            self::specialization('Bundle'),
            self::submitParameters(),
            self::profile(self::IG_PATIENT, 'Patient', []),
            self::profile(self::IG_SUBJECT_OBSERVATION, 'Observation', [
                ['id' => 'Observation.subject', 'path' => 'Observation.subject', 'min' => 0, 'max' => '1', 'type' => [
                    ['code' => 'Reference', 'targetProfile' => [self::IG_PATIENT]],
                ]],
            ]),
            self::profile(self::PATIENT_BUNDLE, 'Bundle', [
                ['id' => 'Bundle.entry.resource', 'path' => 'Bundle.entry.resource', 'min' => 0, 'max' => '1', 'type' => [['code' => 'Patient']]],
            ]),
            self::profile(self::QUANTITY_OBSERVATION, 'Observation', [
                ['id' => 'Observation.value[x]', 'path' => 'Observation.value[x]', 'min' => 0, 'max' => '1', 'type' => [['code' => 'Quantity']]],
                ['id' => 'Observation.subject', 'path' => 'Observation.subject', 'min' => 0, 'max' => '1', 'type' => [
                    ['code' => 'Reference', 'targetProfile' => [self::CORE . 'Patient']],
                ]],
            ]),
        ]));

        $this->service    = OracleValidationServiceFactory::create(FhirVersion::R4, false, $this->resolver);
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testValueOfTheSliceTypePasses(): void
    {
        self::assertSame([], $this->findings(self::SUBMIT, self::parameters(['name' => 'requisition', 'valueString' => 'REQ-1'])));
    }

    public function testValueOfAnotherTypeInASliceIsReportedAtTheItem(): void
    {
        self::assertSame(
            [['parameter[0].value[x]', "The type 'integer' is not allowed here; the profile allows string"]],
            $this->findings(self::SUBMIT, self::parameters(['name' => 'requisition', 'valueInteger' => 5])),
        );
    }

    public function testReferenceToAnotherResourceTypeInASliceIsReported(): void
    {
        self::assertSame(
            [['parameter[1].value[x]', 'Invalid Resource target type. Found Patient, but expected one of (PractitionerRole)']],
            $this->findings(self::SUBMIT, self::parameters(
                ['name' => 'requisition', 'valueString' => 'REQ-1'],
                ['name' => 'copy-to', 'valueReference' => ['reference' => 'Patient/1']],
            )),
        );
        self::assertSame([], $this->findings(self::SUBMIT, self::parameters(
            ['name' => 'requisition', 'valueString' => 'REQ-1'],
            ['name' => 'copy-to', 'valueReference' => ['reference' => 'PractitionerRole/1']],
        )));
    }

    public function testTopLevelTypeAndTargetRestrictionsAreEnforced(): void
    {
        $observation = ['resourceType' => 'Observation', 'status' => 'final', 'code' => ['text' => 'x']];

        self::assertSame(
            [
                ['value[x]', "The type 'string' is not allowed here; the profile allows Quantity"],
                ['subject', 'Invalid Resource target type. Found Group, but expected one of (Patient)'],
            ],
            $this->findings(self::QUANTITY_OBSERVATION, [...$observation, 'valueString' => 'high', 'subject' => ['reference' => 'Group/1']]),
        );
        self::assertSame(
            [],
            $this->findings(self::QUANTITY_OBSERVATION, [...$observation, 'valueQuantity' => ['value' => 1], 'subject' => ['reference' => 'Patient/1']]),
        );
    }

    /**
     * A target that is an IG profile allows the type the profile constrains, read from its loaded
     * StructureDefinition, so the reference is checked without being resolved (#246).
     */
    public function testReferenceIsCheckedAgainstTheTypeAnIgProfileTargetConstrains(): void
    {
        $observation = ['resourceType' => 'Observation', 'status' => 'final', 'code' => ['text' => 'x']];

        self::assertSame(
            [['subject', 'Invalid Resource target type. Found Group, but expected one of (Patient)']],
            $this->findings(self::IG_SUBJECT_OBSERVATION, [...$observation, 'subject' => ['reference' => 'Group/1']]),
        );
        self::assertSame([], $this->findings(self::IG_SUBJECT_OBSERVATION, [...$observation, 'subject' => ['reference' => 'Patient/1']]));
    }

    /**
     * `Bundle.entry.resource only Patient` rejects an entry holding another resource type (#246).
     * A profile rule under a repeating parent reports the path without the item index, as every
     * FHIRProfileConstraint finding does today.
     */
    public function testNarrowedResourceSlotRejectsAnotherResourceType(): void
    {
        $bundle = ['resourceType' => 'Bundle', 'type' => 'collection', 'entry' => [
            ['fullUrl' => 'urn:uuid:9d7c2a3e-8a4f-4f43-9e3b-6b1c2d3e4f50', 'resource' => ['resourceType' => 'Patient']],
            ['fullUrl' => 'urn:uuid:9d7c2a3e-8a4f-4f43-9e3b-6b1c2d3e4f51', 'resource' => ['resourceType' => 'Observation', 'status' => 'final', 'code' => ['text' => 'x']]],
        ]];

        self::assertSame(
            [['entry.resource', "The type 'Observation' is not allowed here; the profile allows Patient"]],
            $this->findings(self::PATIENT_BUNDLE, $bundle),
        );
    }

    /**
     * A published differential carries no `base`, so the slot is told by the element's path: the R5
     * core `subscription-notification-bundle` narrows `entry:first.resource` to SubscriptionStatus.
     */
    public function testPublishedProfileNarrowingAResourceSlotCompilesToATypeRule(): void
    {
        $json = file_get_contents(__DIR__ . '/../../Fixtures/Profile/core-r5/StructureDefinition-subscription-notification-bundle.json');
        self::assertIsString($json);
        $definition = json_decode($json, true);
        self::assertIsArray($definition);

        /** @var array<string, mixed> $definition */
        $types = [];
        foreach ((new StructureDefinitionCompiler())->compile($definition)->attributes as $attribute) {
            foreach ($attribute->class === FHIRSliceConstraint::class ? (array) ($attribute->arguments['rules'] ?? []) : [] as $rule) {
                if ($rule['constraint'] === FHIRAllowedTypes::class) {
                    $types[] = [$attribute->arguments['sliceName'], $rule['path'], $rule['options']['types']];
                }
            }
        }

        self::assertSame([['first', 'resource', ['SubscriptionStatus']]], $types);
    }

    /**
     * The restrictions are compiled into rules, not skipped, so the compiled chain carries no
     * diagnostic for them.
     */
    public function testRestrictionsCompileWithoutDiagnostics(): void
    {
        foreach ([self::SUBMIT, self::QUANTITY_OBSERVATION, self::IG_SUBJECT_OBSERVATION, self::PATIENT_BUNDLE] as $url) {
            foreach ($this->resolver->resolve($url, 'R4')->chain as $entry) {
                self::assertSame([], $entry->diagnostics, $url);
            }
        }
    }

    /**
     * Profile findings as [path, message], in the order reported.
     *
     * @param array<string, mixed> $json
     *
     * @return list<array{0: string, 1: string}>
     */
    private function findings(string $profileUrl, array $json): array
    {
        $report = $this->service->validateWithOptions(
            $this->serializer->deserialize((string) json_encode($json)),
            new FHIRValidationOptions(profileUrls: [$profileUrl]),
        );

        return array_values(array_map(
            static fn (FHIRValidationViolation $v): array => [$v->path, $v->message],
            array_filter($report->violations, static fn (FHIRValidationViolation $v): bool => $v->profileGroup === $profileUrl),
        ));
    }

    /**
     * @param array<string, mixed> ...$parameters
     *
     * @return array<string, mixed>
     */
    private static function parameters(array ...$parameters): array
    {
        return ['resourceType' => 'Parameters', 'parameter' => $parameters];
    }

    /**
     * The issue's profile, as SUSHI writes its differential, trimmed to what the compiler reads.
     *
     * @return array<string, mixed>
     */
    private static function submitParameters(): array
    {
        $p = 'Parameters.parameter';

        return self::profile(self::SUBMIT, 'Parameters', [
            ['id' => $p, 'path' => $p, 'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'name']], 'rules' => 'closed'], 'min' => 0, 'max' => '*'],
            ['id' => "{$p}:requisition", 'path' => $p, 'sliceName' => 'requisition', 'min' => 1, 'max' => '1'],
            ['id' => "{$p}:requisition.name", 'path' => "{$p}.name", 'min' => 1, 'max' => '1', 'fixedString' => 'requisition'],
            ['id' => "{$p}:requisition.value[x]", 'path' => "{$p}.value[x]", 'min' => 1, 'max' => '1', 'type' => [['code' => 'string']]],
            ['id' => "{$p}:copyTo", 'path' => $p, 'sliceName' => 'copyTo', 'min' => 0, 'max' => '*'],
            ['id' => "{$p}:copyTo.name", 'path' => "{$p}.name", 'min' => 1, 'max' => '1', 'fixedString' => 'copy-to'],
            ['id' => "{$p}:copyTo.value[x]", 'path' => "{$p}.value[x]", 'min' => 1, 'max' => '1', 'type' => [
                ['code' => 'Reference', 'targetProfile' => [self::CORE . 'PractitionerRole']],
            ]],
        ]);
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
     * A constraint profile whose differential and snapshot both hold $elements.
     *
     * @param list<array<string, mixed>> $elements
     *
     * @return array<string, mixed>
     */
    private static function profile(string $url, string $type, array $elements): array
    {
        $elements = [['id' => $type, 'path' => $type], ...$elements];

        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'version'        => '1.0',
            'fhirVersion'    => '4.0.1',
            'kind'           => 'resource',
            'type'           => $type,
            'baseDefinition' => self::CORE . $type,
            'derivation'     => 'constraint',
            'snapshot'       => ['element' => $elements],
            'differential'   => ['element' => $elements],
        ];
    }
}

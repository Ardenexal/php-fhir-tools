<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileInvariant;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * A slice typed with a data-type profile holds the items in that slice to the profile's rules.
 *
 * Modelled on AU Core's `Patient.identifier:ihi`, typed with an IHI profile, written here by hand:
 * the slicing profile discriminates on `type` but only the IHI profile fixes that `type`, so the
 * slice is matched by the type profile's pattern. The IHI profile's own invariants check the
 * number's length and prefix, and its system is fixed.
 */
#[CoversClass(FHIRSliceConstraintValidator::class)]
final class RuntimeSliceDataTypeProfileTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string PATIENT = 'http://example.org/fhir/StructureDefinition/national-patient';

    private const string IHI = 'http://example.org/fhir/StructureDefinition/ihi';

    private const string IHI_SYSTEM = 'http://example.org/id/ihi';

    private const array NI_TYPE = ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v2-0203', 'code' => 'NI']]];

    private FHIRValidationService $service;

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $ihi = [
            ['id' => 'Identifier', 'path' => 'Identifier', 'constraint' => [
                ['key' => 'ihi-1', 'severity' => 'error', 'expression' => "value.matches('^[0-9]{16}$')", 'human' => 'An IHI is 16 digits'],
                ['key' => 'ihi-2', 'severity' => 'error', 'expression' => "value.startsWith('800360')", 'human' => 'An IHI starts with 800360'],
            ]],
            ['id' => 'Identifier.type', 'path' => 'Identifier.type', 'min' => 1, 'patternCodeableConcept' => self::NI_TYPE],
            ['id' => 'Identifier.system', 'path' => 'Identifier.system', 'min' => 1, 'fixedUri' => self::IHI_SYSTEM],
        ];

        $patient = [
            ['id' => 'Patient', 'path' => 'Patient'],
            ['id' => 'Patient.identifier', 'path' => 'Patient.identifier', 'slicing' => [
                'discriminator' => [['type' => 'pattern', 'path' => 'type']],
                'rules'         => 'open',
            ]],
            ['id' => 'Patient.identifier:ihi', 'path' => 'Patient.identifier', 'sliceName' => 'ihi', 'min' => 0, 'max' => '1', 'type' => [
                ['code' => 'Identifier', 'profile' => [self::IHI]],
            ]],
        ];

        $registry = new InMemoryStructureDefinitionRegistry([
            self::definition(self::CORE . 'Patient', 'resource', 'Patient', 'specialization', [['id' => 'Patient', 'path' => 'Patient']]),
            self::definition(self::CORE . 'Identifier', 'complex-type', 'Identifier', 'specialization', [['id' => 'Identifier', 'path' => 'Identifier']]),
            self::definition(self::IHI, 'complex-type', 'Identifier', 'constraint', $ihi),
            self::definition(self::PATIENT, 'resource', 'Patient', 'constraint', $patient),
        ]);

        $this->service    = OracleValidationServiceFactory::create(FhirVersion::R4, false, new RuntimeProfileResolver($registry));
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testAValidIhiHasNoFindings(): void
    {
        self::assertSame([], $this->findings([self::ihi('8003608166690503')]));
    }

    /**
     * Only the identifier in the IHI slice is held to the IHI profile, so a Medicare number beside
     * it with a different system and length is left alone.
     */
    public function testABadIhiIsReportedAtItsIdentifier(): void
    {
        $medicare = ['type' => ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v2-0203', 'code' => 'MC']]], 'system' => 'http://example.org/id/medicare', 'value' => '6951449677'];

        self::assertSame(
            [['identifier[1]', 'An IHI starts with 800360', FHIRProfileInvariant::class, 'ihi-2']],
            $this->findings([$medicare, self::ihi('9003608166690503')]),
        );
    }

    public function testTheProfilesFixedSystemHoldsInTheSlice(): void
    {
        $findings = $this->findings([[...self::ihi('8003608166690503'), 'system' => 'http://example.org/id/other']]);

        self::assertSame(['identifier[0].system'], array_column($findings, 0));
    }

    /**
     * The profile's findings, sorted, as path, message, constraint and invariant key.
     *
     * @param list<array<string, mixed>> $identifiers
     *
     * @return list<array{string, string, string, string}>
     */
    private function findings(array $identifiers): array
    {
        $json    = (string) json_encode(['resourceType' => 'Patient', 'identifier' => $identifiers]);
        $patient = $this->serializer->deserialize($json);

        $found = array_map(
            static fn (FHIRValidationViolation $v): array => [$v->path, $v->message, $v->constraintClass, (string) $v->invariantKey],
            array_values(array_filter(
                $this->service->validate($patient, [self::PATIENT])->violations,
                static fn (FHIRValidationViolation $v): bool => $v->profileGroup === self::PATIENT && $v->severity === 'error',
            )),
        );
        sort($found);

        return $found;
    }

    /**
     * @return array<string, mixed>
     */
    private static function ihi(string $value): array
    {
        return ['type' => self::NI_TYPE, 'system' => self::IHI_SYSTEM, 'value' => $value];
    }

    /**
     * @param list<array<string, mixed>> $elements
     *
     * @return array<string, mixed>
     */
    private static function definition(string $url, string $kind, string $type, string $derivation, array $elements): array
    {
        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'fhirVersion'    => '4.0.1',
            'kind'           => $kind,
            'type'           => $type,
            'derivation'     => $derivation,
            'baseDefinition' => $derivation === 'constraint' ? self::CORE . $type : null,
            'snapshot'       => ['element' => $elements],
            'differential'   => ['element' => $elements],
        ];
    }
}

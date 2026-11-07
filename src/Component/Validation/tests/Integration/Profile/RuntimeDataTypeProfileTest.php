<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Profile;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\QuantityComparatorType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\InMemoryStructureDefinitionRegistry;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Tests\Integration\Oracle\OracleValidationServiceFactory;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;

/**
 * A runtime profile that types an element with a data-type profile enforces that profile's rules on
 * every value of the element, including through repeating elements.
 */
#[CoversClass(FHIRValidationService::class)]
#[CoversClass(RuntimeProfileResolver::class)]
#[CoversClass(FHIRSliceConstraintValidator::class)]
final class RuntimeDataTypeProfileTest extends TestCase
{
    private const string CORE = 'http://hl7.org/fhir/StructureDefinition/';

    private const string BASE = 'http://example.org/fhir/StructureDefinition/';

    private const string PROFILE = self::BASE . 'observation-with-datatypes';

    private FHIRValidationService $service;

    private RuntimeProfileResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new RuntimeProfileResolver(new InMemoryStructureDefinitionRegistry([
            self::definition(self::CORE . 'Observation', 'resource', 'Observation', 'specialization', []),
            self::definition(self::PROFILE, 'resource', 'Observation', 'constraint', [
                self::typed('Observation.identifier', 'Identifier', self::BASE . 'kinded-identifier'),
                self::typed('Observation.value[x]:valueQuantity', 'Quantity', self::BASE . 'plain-quantity', 'Observation.value[x]'),
                self::typed('Observation.focus.identifier', 'Identifier', self::BASE . 'loop-a'),
            ]),
            self::definition(self::BASE . 'kinded-identifier', 'complex-type', 'Identifier', 'constraint', [
                [
                    'id'      => 'Identifier.type.coding',
                    'path'    => 'Identifier.type.coding',
                    'slicing' => ['discriminator' => [['type' => 'value', 'path' => 'system']], 'rules' => 'open'],
                ],
                ['id' => 'Identifier.type.coding:kind', 'path' => 'Identifier.type.coding', 'sliceName' => 'kind', 'min' => 1, 'max' => '1'],
                ['id' => 'Identifier.type.coding:kind.system', 'path' => 'Identifier.type.coding.system', 'fixedUri' => 'http://example.org/kinds'],
                // A nested data-type profile, inlined under identifier.assigner.identifier
                self::typed('Identifier.assigner.identifier', 'Identifier', self::BASE . 'system-required'),
            ]),
            self::definition(self::BASE . 'system-required', 'complex-type', 'Identifier', 'constraint', [
                ['id' => 'Identifier.system', 'path' => 'Identifier.system', 'min' => 1],
            ]),
            self::definition(self::BASE . 'plain-quantity', 'complex-type', 'Quantity', 'constraint', [
                ['id' => 'Quantity.comparator', 'path' => 'Quantity.comparator', 'max' => '0'],
            ]),
            // Two profiles that contain each other: compiling must stop, not recurse forever
            self::definition(self::BASE . 'loop-a', 'complex-type', 'Identifier', 'constraint', [
                self::typed('Identifier.assigner.identifier', 'Identifier', self::BASE . 'loop-b'),
            ]),
            self::definition(self::BASE . 'loop-b', 'complex-type', 'Identifier', 'constraint', [
                self::typed('Identifier.assigner.identifier', 'Identifier', self::BASE . 'loop-a'),
            ]),
        ]));
        $this->service = OracleValidationServiceFactory::create(FhirVersion::R4, false, $this->resolver);
    }

    public function testSliceOfAnInlinedProfileHoldsForEachRepetition(): void
    {
        $observation             = new ObservationResource();
        $observation->identifier = [self::identifier('http://example.org/kinds'), self::identifier('http://example.org/other')];

        // The first identifier has its kind coding; only the second one is short of it
        self::assertSame([], $this->profileFindings($observation, 'identifier[0].type.coding'));
        $slices = $this->profileFindings($observation, 'identifier[1].type.coding');
        self::assertCount(1, $slices);
        self::assertStringContainsString('Slice "kind" on "identifier[1].type.coding" requires at least 1', $slices[0]->message);
    }

    public function testEachRepetitionThatBreaksASliceIsNamed(): void
    {
        $observation             = new ObservationResource();
        $observation->identifier = [self::identifier('http://example.org/other'), self::identifier('http://example.org/other')];

        self::assertCount(1, $this->profileFindings($observation, 'identifier[0].type.coding'));
        self::assertCount(1, $this->profileFindings($observation, 'identifier[1].type.coding'));
    }

    /**
     * An array whose keys are not 0..n-1, such as one filtered by the caller, is named by its own keys.
     */
    public function testARepetitionIsNamedByItsOwnKey(): void
    {
        $observation             = new ObservationResource();
        $observation->identifier = [0 => self::identifier('http://example.org/kinds'), 2 => self::identifier('http://example.org/other')];

        self::assertSame([], $this->profileFindings($observation, 'identifier[1].type.coding'));
        self::assertCount(1, $this->profileFindings($observation, 'identifier[2].type.coding'));
    }

    /**
     * A compiled profile inlines other definitions, so it is compiled again when one of them changes.
     */
    public function testAddingAnInlinedProfileLaterIsPickedUp(): void
    {
        $registry = new InMemoryStructureDefinitionRegistry([
            self::definition(self::CORE . 'Observation', 'resource', 'Observation', 'specialization', []),
            self::definition(self::PROFILE, 'resource', 'Observation', 'constraint', [
                self::typed('Observation.value[x]:valueQuantity', 'Quantity', self::BASE . 'plain-quantity', 'Observation.value[x]'),
            ]),
        ]);
        $resolver = new RuntimeProfileResolver($registry);

        self::assertSame([], $resolver->resolve(self::PROFILE, 'R4')->chain[0]->attributes);

        $registry->add(self::definition(self::BASE . 'plain-quantity', 'complex-type', 'Quantity', 'constraint', [
            ['id' => 'Quantity.comparator', 'path' => 'Quantity.comparator', 'max' => '0'],
        ]));

        self::assertCount(1, $resolver->resolve(self::PROFILE, 'R4')->chain[0]->attributes);
    }

    public function testRulesApplyOnAChoiceVariant(): void
    {
        $quantity             = new Quantity();
        $quantity->comparator = new QuantityComparatorType('<');
        $observation          = new ObservationResource();
        $observation->value   = $quantity;

        self::assertCount(1, $this->profileFindings($observation, 'valueQuantity.comparator'));

        $observation->value = new Quantity();
        self::assertSame([], $this->profileFindings($observation, 'valueQuantity.comparator'));
    }

    public function testNestedProfilesAreEnforcedUnderTheirElement(): void
    {
        $assigned                       = self::identifier('http://example.org/kinds');
        $assigned->assigner             = new Reference();
        $assigned->assigner->identifier = new Identifier();
        $observation                    = new ObservationResource();
        $observation->identifier        = [$assigned];

        self::assertCount(1, $this->profileFindings($observation, 'identifier.assigner.identifier.system'));
    }

    public function testACycleCompilesOnceAndIsReported(): void
    {
        $resolution = $this->resolver->resolve(self::PROFILE, 'R4');

        self::assertTrue($resolution->isResolved(), (string) $resolution->message);
        $messages = array_map(static fn ($d): string => $d->message, $resolution->chain[0]->diagnostics);
        self::assertCount(1, array_filter($messages, static fn (string $m): bool => str_contains($m, 'is already being inlined on this path')));
    }

    /**
     * Findings in the profile's group at one path.
     *
     * @return list<FHIRValidationViolation>
     */
    private function profileFindings(ObservationResource $observation, string $path): array
    {
        return array_values(array_filter(
            $this->service->validate($observation, [self::PROFILE])->violations,
            static fn (FHIRValidationViolation $v): bool => $v->profileGroup === self::PROFILE && $v->path === $path,
        ));
    }

    private static function identifier(string $codingSystem): Identifier
    {
        $coding              = new Coding();
        $coding->system      = new UriPrimitive(value: $codingSystem);
        $type                = new CodeableConcept();
        $type->coding        = [$coding];
        $identifier          = new Identifier();
        $identifier->type    = $type;

        return $identifier;
    }

    /**
     * @return array<string, mixed>
     */
    private static function typed(string $id, string $code, string $profile, ?string $path = null): array
    {
        return ['id' => $id, 'path' => $path ?? $id, 'type' => [['code' => $code, 'profile' => [$profile]]]];
    }

    /**
     * @param list<array<string, mixed>> $elements
     *
     * @return array<string, mixed>
     */
    private static function definition(string $url, string $kind, string $type, string $derivation, array $elements): array
    {
        $elements = [['id' => $type, 'path' => $type], ...$elements];

        return [
            'resourceType'   => 'StructureDefinition',
            'url'            => $url,
            'fhirVersion'    => '4.0.1',
            'kind'           => $kind,
            'type'           => $type,
            'baseDefinition' => self::CORE . $type,
            'derivation'     => $derivation,
            'snapshot'       => ['element' => $elements],
            'differential'   => ['element' => $elements],
        ];
    }
}

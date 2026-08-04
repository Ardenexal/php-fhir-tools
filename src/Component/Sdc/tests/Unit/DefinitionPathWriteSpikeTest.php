<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Sdc\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\Primitive\FHIRDate;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DatePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadata;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProvider;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\PropertyMetadataProviderInterface;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

/**
 * M01 RISKY spike — proves the single hardest primitive definition-based `$extract` (M02) depends on:
 * a **generic** writer that sets a value into a typed generated resource addressed by a canonical
 * `definition` path, sourcing cardinality/type from `#[FhirProperty]` metadata (via the same
 * {@see PropertyMetadataProvider} the serializer uses) with **no per-resource-type code**.
 *
 * A flat `definition` path carries no array index and no concrete choice type, so the writer must
 * decide append-vs-set from cardinality and resolve `value[x]` from the choice variant metadata.
 * This is proven across three structurally different shapes:
 *
 *   1. nested-list append — `Patient.name.given` (instantiate `HumanName` at `name[0]`, append to `given`);
 *   2. choice `value[x]`  — `Observation.value[x]` (resolve the polymorphic variant from the value's type);
 *   3. scalar             — `Patient.birthDate` (a non-repeating primitive).
 *
 * Each written value is asserted to round-trip through serialization.
 *
 * Kill criteria (blocks M02, NOT this milestone): if any shape needs per-resource-type code —
 * i.e. {@see SpikeDefinitionPathWriter} would need an `if ($type === 'Quantity')`-style branch —
 * definition-based extraction is unviable as designed and M02 must be re-scoped. The writer below
 * contains no such branch; it dispatches purely on `PropertyMetadata` (`isArray`, `isChoice`,
 * `variants`, `phpItemClass`).
 */
#[CoversNothing]
final class DefinitionPathWriteSpikeTest extends TestCase
{
    private SpikeDefinitionPathWriter $writer;

    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->writer     = new SpikeDefinitionPathWriter(new PropertyMetadataProvider());
        $this->serializer = FHIRSerializationService::createDefault(FhirVersion::R4);
    }

    public function testNestedListAppendShape(): void
    {
        $patient = new PatientResource();

        // A flat definition path: no `name[0]` index — the writer must instantiate the HumanName
        // intermediate (name is 1..*) and append to given (also 1..*), both decided from cardinality.
        $this->writer->write($patient, 'Patient.name.given', 'Peter');
        $this->writer->write($patient, 'Patient.name.given', 'James');

        $decoded = $this->roundTrip($patient);

        self::assertSame(['Peter', 'James'], $decoded['name'][0]['given'] ?? null);
    }

    public function testChoiceValueXShapeResolvesVariantFromValueType(): void
    {
        $observation = new ObservationResource();
        $quantity    = new Quantity(value: '185', unit: 'cm');

        // `value[x]` carries no concrete type in the path; the writer resolves the Quantity variant
        // purely from the runtime value's type against the choice `variants` metadata.
        $this->writer->write($observation, 'Observation.value[x]', $quantity);

        $decoded = $this->roundTrip($observation);

        // The serializer emits the variant's jsonKey (valueQuantity), NOT a plain `value` — proof the
        // choice landed on the resolved variant. The decimal is rendered as a JSON number.
        self::assertArrayHasKey('valueQuantity', $decoded);
        self::assertArrayNotHasKey('value', $decoded);
        self::assertIsArray($decoded['valueQuantity']);
        self::assertEquals(185, $decoded['valueQuantity']['value'] ?? null);
        self::assertSame('cm', $decoded['valueQuantity']['unit'] ?? null);
    }

    public function testScalarPrimitiveShape(): void
    {
        $patient = new PatientResource();

        $this->writer->write($patient, 'Patient.birthDate', new DatePrimitive(value: FHIRDate::parse('1990-01-01')));

        $decoded = $this->roundTrip($patient);

        self::assertSame('1990-01-01', $decoded['birthDate'] ?? null);
    }

    public function testWritesIntoDeserializerOriginObjectWithUninitializedIntermediate(): void
    {
        // Deserializer-origin objects can have uninitialized typed properties (the model-init footgun).
        // The writer must tolerate reading a possibly-uninitialized intermediate before writing.
        $patient = $this->serializer->deserializeFromJson(
            '{"resourceType":"Patient","id":"example"}',
            PatientResource::class,
        );
        self::assertInstanceOf(PatientResource::class, $patient);

        $this->writer->write($patient, 'Patient.name.given', 'Peter');

        $decoded = $this->roundTrip($patient);

        self::assertSame(['Peter'], $decoded['name'][0]['given'] ?? null);
    }

    /**
     * @return array<string, mixed>
     */
    private function roundTrip(object $resource): array
    {
        $decoded = json_decode($this->serializer->serializeToJson($resource), true);
        self::assertIsArray($decoded);

        /** @var array<string, mixed> $decoded */
        return $decoded;
    }
}

/**
 * The spike's generic definition-path writer. Deliberately lives in the test file (not `src/`): it is
 * feasibility proof, not the M02 production `DefinitionPathWriter`. Its whole point is that its body
 * contains no resource-type or FHIR-type switch — every decision is driven by {@see PropertyMetadata}.
 *
 * @internal spike-only
 */
final class SpikeDefinitionPathWriter
{
    public function __construct(
        private readonly PropertyMetadataProviderInterface $provider,
    ) {
    }

    /**
     * Set `$value` into `$root` at the canonical element path `$definition`
     * (e.g. `Patient.name.given`, `Observation.value[x]`, or a full canonical URL with a `#`-fragment).
     */
    public function write(object $root, string $definition, mixed $value): void
    {
        $segments = explode('.', $this->elementPath($definition));
        array_shift($segments); // drop the leading resource type (e.g. "Patient")
        $leaf = array_pop($segments);
        if ($leaf === null) {
            throw new \InvalidArgumentException("Definition path has no element: {$definition}");
        }

        $current = $root;
        foreach ($segments as $segment) {
            $current = $this->descend($current, $this->normalizeSegment($segment));
        }

        $this->setLeaf($current, $this->normalizeSegment($leaf), $value);
    }

    /**
     * Extract the element path from a definition: the fragment after `#`, or the string as-is.
     */
    private function elementPath(string $definition): string
    {
        $hash = strpos($definition, '#');

        return $hash === false ? $definition : substr($definition, $hash + 1);
    }

    /**
     * Strip a choice element's `[x]` marker so it maps to the backing PHP property (`value[x]` -> `value`).
     */
    private function normalizeSegment(string $segment): string
    {
        return str_ends_with($segment, '[x]') ? substr($segment, 0, -3) : $segment;
    }

    private function metaFor(object $object, string $property): PropertyMetadata
    {
        $map = $this->provider->getPropertyMetadata($object::class);

        return $map[$property]
            ?? throw new \RuntimeException(\sprintf('No #[FhirProperty] metadata for %s::$%s', $object::class, $property));
    }

    /**
     * Navigate into an intermediate element, instantiating it (and its list slot) if absent.
     */
    private function descend(object $current, string $property): object
    {
        $meta  = $this->metaFor($current, $property);
        $class = $meta->phpItemClass
            ?? throw new \RuntimeException(\sprintf('Intermediate %s::$%s has no phpType to instantiate', $current::class, $property));

        if ($meta->isArray) {
            $list = $this->readArray($current, $property);
            if (($list[0] ?? null) instanceof $class) {
                return $list[0];
            }
            $child              = new $class();
            $list[0]            = $child;
            $current->$property = $list;

            return $child;
        }

        $child = $current->$property ?? null;
        if (!$child instanceof $class) {
            $child              = new $class();
            $current->$property = $child;
        }

        return $child;
    }

    private function setLeaf(object $current, string $property, mixed $value): void
    {
        $meta = $this->metaFor($current, $property);

        if ($meta->isChoice) {
            // Resolve which polymorphic variant this value is — proof the choice is metadata-driven.
            // The single backing property holds the union, so assignment itself is type-agnostic.
            $this->resolveChoiceVariant($meta, $value);
            $current->$property = $value;

            return;
        }

        if ($meta->isArray) {
            $list               = $this->readArray($current, $property);
            $list[]             = $value;
            $current->$property = $list;

            return;
        }

        $current->$property = $value;
    }

    /**
     * @return array<int, mixed>
     */
    private function readArray(object $current, string $property): array
    {
        // `??` uses isset() semantics: an uninitialized typed property (deserializer-origin object)
        // reads as absent instead of throwing \Error — the model-init footgun, handled generically.
        $value = $current->$property ?? [];

        return is_array($value) ? array_values($value) : [];
    }

    private function resolveChoiceVariant(PropertyMetadata $meta, mixed $value): void
    {
        foreach ($meta->variants ?? [] as $variant) {
            if ($this->valueMatchesType($value, $variant->phpType)) {
                return;
            }
        }

        throw new \RuntimeException(\sprintf('No choice variant matches value of type %s', get_debug_type($value)));
    }

    private function valueMatchesType(mixed $value, string $phpType): bool
    {
        return match ($phpType) {
            'bool'   => is_bool($value),
            'int'    => is_int($value),
            'float'  => is_float($value),
            'string' => is_string($value),
            default  => $value instanceof $phpType,
        };
    }
}

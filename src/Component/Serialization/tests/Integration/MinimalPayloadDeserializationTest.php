<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuClinicalDocument;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * A denormalized instance must be in the state the constructor would have produced.
 *
 * The denormalizers bypass the constructor, so before this was fixed every property the constructor
 * would have defaulted was left *uninitialized* — a third state distinct from both the declared
 * default and null, and the only one that throws on read. `?->` does not help: the null-safe operator
 * guards against null, and uninitialized is not null.
 *
 * The fixtures here carry the required fields and nothing else, which is what makes the defect
 * visible at all: any payload that populates a field works, so a suite of representative fixtures
 * stays green while an absent optional element — the common case in production — throws on read.
 */
#[CoversClass(FHIRSerializationService::class)]
final class MinimalPayloadDeserializationTest extends TestCase
{
    /** A Patient carrying required fields only: every optional element is absent. */
    private const string MINIMAL_PATIENT_JSON = <<<'JSON'
        {"resourceType":"Patient","id":"example"}
        JSON;

    /** The same resource in XML, so the XML denormalizer path is held to the same guarantee. */
    private const string MINIMAL_PATIENT_XML = <<<'XML'
        <Patient xmlns="http://hl7.org/fhir"><id value="example"/></Patient>
        XML;

    /**
     * Reading an absent optional element returns null instead of throwing.
     *
     * @param non-empty-string $property
     */
    #[DataProvider('absentOptionalProperties')]
    public function testAbsentOptionalPropertiesReadAsNull(string $property): void
    {
        $patient = $this->deserializeMinimalPatient();

        self::assertNull((new \ReflectionProperty(PatientResource::class, $property))->getValue($patient));
    }

    /**
     * The state is a *declared* null, not the uninitialized slot that reads back as null through
     * Symfony's property accessor while throwing for a direct read.
     *
     * @param non-empty-string $property
     */
    #[DataProvider('absentOptionalProperties')]
    public function testAbsentOptionalPropertiesAreInitialized(string $property): void
    {
        $patient = $this->deserializeMinimalPatient();

        self::assertTrue((new \ReflectionProperty(PatientResource::class, $property))->isInitialized($patient));
    }

    /**
     * @return iterable<string, array{0: non-empty-string}>
     */
    public static function absentOptionalProperties(): iterable
    {
        yield 'gender'        => ['gender'];
        yield 'birthDate'     => ['birthDate'];
        yield 'maritalStatus' => ['maritalStatus'];
        // The deceased[x] choice, which the payload supplies under neither of its keys.
        yield 'deceased' => ['deceased'];
    }

    /**
     * State equivalence as one property-based check rather than a field list: for a resource
     * deserialized from a minimal payload, every public property the constructor defaults is
     * initialized and holds the freshly-constructed instance's value.
     */
    public function testDeserializedInstanceMatchesTheConstructedState(): void
    {
        $deserialized = $this->deserializeMinimalPatient();
        $constructed  = new PatientResource();

        $divergent = [];
        $checked   = 0;

        foreach ((new \ReflectionClass(PatientResource::class))->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            // Only properties the constructor gives a value to: where it declares no default, an
            // uninitialized slot is the model's own choice and not an artefact of skipping it.
            if ($property->isStatic() || !$property->isInitialized($constructed)) {
                continue;
            }

            // Supplied by the payload, so it is expected to differ.
            if ($property->getName() === 'id') {
                continue;
            }

            ++$checked;

            if (!$property->isInitialized($deserialized)) {
                $divergent[] = $property->getName() . ' (uninitialized)';
                continue;
            }

            if ($property->getValue($deserialized) != $property->getValue($constructed)) {
                $divergent[] = $property->getName() . ' (value differs)';
            }
        }

        self::assertGreaterThan(0, $checked, 'No constructor-defaulted properties were compared');
        self::assertSame([], $divergent);
    }

    /**
     * The repeating-element guarantee this pass originally existed for still holds: a non-nullable
     * `array` property is `[]`, never null and never uninitialized, so the generated
     * `#[Count(min: 1)]` constraints still see a countable value.
     */
    public function testRepeatingElementsAreEmptyArraysNotNull(): void
    {
        $patient = $this->deserializeMinimalPatient();

        foreach (['identifier', 'name', 'telecom', 'address'] as $repeating) {
            $property = new \ReflectionProperty(PatientResource::class, $repeating);

            self::assertTrue($property->isInitialized($patient), "{$repeating} is uninitialized");
            self::assertSame([], $property->getValue($patient), "{$repeating} is not an empty array");
        }
    }

    /**
     * The XML denormalizer bypasses the constructor the same way the JSON one does, and gets the
     * same guarantee.
     */
    public function testXmlDeserializationLeavesNoUninitializedDefaults(): void
    {
        $patient = FHIRSerializationService::createDefault()
            ->deserializeFromXml(self::MINIMAL_PATIENT_XML, PatientResource::class);

        self::assertNull($patient->gender);
        self::assertNull($patient->birthDate);
        self::assertSame([], $patient->name);
    }

    /**
     * Defaults are matched by constructor *parameter name*, which is what reaches inherited
     * properties: CDA subclasses re-declare inherited elements as non-promoted passthrough
     * parameters forwarded via `parent::__construct()`, so the default for a parent-declared
     * property is found on the child's own constructor and nowhere else.
     */
    public function testInheritedPropertiesOfASubclassAreDefaulted(): void
    {
        $document = FHIRSerializationService::createWithIG(version: FhirVersion::R5)
            ->deserializeFromXml(
                '<ClinicalDocument xmlns="urn:hl7-org:v3"><id root="1.2.3"/></ClinicalDocument>',
                AuClinicalDocument::class,
            );

        $inherited = new \ReflectionProperty(AuClinicalDocument::class, 'confidentialityCode');
        self::assertNotSame(
            AuClinicalDocument::class,
            $inherited->getDeclaringClass()->getName(),
            'confidentialityCode is expected to be inherited, not declared on the subclass',
        );

        self::assertTrue($inherited->isInitialized($document));
        self::assertNull($document->confidentialityCode);
        self::assertSame([], $document->recordTarget);
    }

    private function deserializeMinimalPatient(): PatientResource
    {
        $patient = FHIRSerializationService::createDefault()
            ->deserializeFromJson(self::MINIMAL_PATIENT_JSON, PatientResource::class);

        self::assertInstanceOf(PatientResource::class, $patient);

        return $patient;
    }
}

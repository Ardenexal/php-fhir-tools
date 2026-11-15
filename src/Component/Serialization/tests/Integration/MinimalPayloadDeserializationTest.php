<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuClinicalDocument;
use Ardenexal\FHIRTools\Component\Models\R4\Extension\MinLengthExtension;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;
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
     * A Patient carrying one bare primitive and one complex element, each supplying only its own
     * value: enough to build a primitive wrapper and a nested object, with neither one's `id` nor
     * `extension` metadata present in the payload.
     */
    private const string PATIENT_WITH_GENDER_JSON = <<<'JSON'
        {"resourceType":"Patient","id":"example","gender":"male","identifier":[{"value":"abc"}]}
        JSON;

    /** The same resource in XML. */
    private const string PATIENT_WITH_GENDER_XML = <<<'XML'
        <Patient xmlns="http://hl7.org/fhir"><id value="example"/><identifier><value value="abc"/></identifier><gender value="male"/></Patient>
        XML;

    /** A typed extension present in the payload but carrying no value of its own. */
    private const string MINIMAL_QUESTIONNAIRE_JSON = <<<'JSON'
        {"resourceType":"Questionnaire","id":"example","status":"draft","item":[{"linkId":"1","type":"string","extension":[{"url":"http://hl7.org/fhir/StructureDefinition/minLength"}]}]}
        JSON;

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
     * Defaults are matched by constructor *parameter name*, resolved across the whole class
     * hierarchy. CDA subclasses re-declare inherited elements as non-promoted passthrough parameters
     * forwarded via `parent::__construct()`, so a child-only lookup already finds these. It is the
     * kinds that hide a parameter instead of re-declaring it, covered by the two tests below, that
     * make the ancestor walk necessary.
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

    /**
     * A primitive wrapper carries the same guarantee as the resource that holds it.
     *
     * Primitives are not built by the shared instantiation pass but by `createPrimitiveInstance()`,
     * which assigns `value`, and assigns `extension` only when the payload carries one. A payload
     * supplying a bare `"gender":"male"` therefore left `id` and `extension` unassigned, so the
     * element read back fine while reading its own metadata threw.
     *
     * @param non-empty-string $format
     */
    #[DataProvider('bothSerializationFormats')]
    public function testPrimitiveWrapperMetadataIsInitialized(string $format): void
    {
        $patient = $this->deserializePatientWithGender($format);

        self::assertNotNull($patient->gender, 'the payload supplies gender, so it must be present');
        self::assertNull($patient->gender->id);
        self::assertSame([], $patient->gender->extension);
    }

    /**
     * The hidden slots of a *typed extension* get their ancestor's defaults too.
     *
     * A typed extension declares `id` and its own `value<Type>`, but passes `url`, and the inherited
     * `value` it narrowed, to `parent::__construct()` as computed arguments, so a child-only lookup
     * never saw those two. `url` survived anyway because every extension payload carries one, and
     * `value` survived whenever the payload supplied one, so only an extension with an absent
     * optional value threw on `->value`.
     */
    public function testTypedExtensionWithAbsentValueReadsAsNull(): void
    {
        $questionnaire = FHIRSerializationService::createDefault()
            ->deserializeFromJson(self::MINIMAL_QUESTIONNAIRE_JSON, QuestionnaireResource::class);

        self::assertInstanceOf(QuestionnaireResource::class, $questionnaire);

        $extension = $questionnaire->item[0]->extension[0];
        self::assertInstanceOf(MinLengthExtension::class, $extension);

        // Nothing here is supplied by the payload: `value` and `id` come from the ancestor,
        // `valueInteger` from MinLengthExtension itself.
        self::assertNull($extension->value);
        self::assertNull($extension->valueInteger);
        self::assertNull($extension->id);
        // url is declared on the same ancestor as `value`, and the payload does supply it.
        self::assertSame('http://hl7.org/fhir/StructureDefinition/minLength', $extension->url);
    }

    /**
     * The guarantee holds for every object in a deserialized graph, not just its root.
     *
     * Stated as a reachability sweep rather than a field list because the field lists are what kept
     * missing this: a minimal Patient builds no primitive wrapper and no complex type at all, so a
     * suite asserting on the root stayed green while everything nested below it threw.
     *
     * @param non-empty-string $format
     */
    #[DataProvider('bothSerializationFormats')]
    public function testNoObjectInTheGraphHasAnUninitializedProperty(string $format): void
    {
        $uninitialized = [];
        $this->collectUninitializedProperties($this->deserializePatientWithGender($format), $uninitialized);

        self::assertSame([], $uninitialized);
    }

    /**
     * @return iterable<string, array{0: non-empty-string}>
     */
    public static function bothSerializationFormats(): iterable
    {
        yield 'json' => ['json'];
        yield 'xml'  => ['xml'];
    }

    /**
     * Every class reachable from this fixture declares a default for every constructor parameter,
     * so any uninitialized property found below it is the constructor-bypass artefact.
     *
     * @param list<string> $uninitialized
     * @param list<object> $seen
     */
    private function collectUninitializedProperties(object $object, array &$uninitialized, array &$seen = []): void
    {
        if (in_array($object, $seen, true)) {
            return;
        }
        $seen[] = $object;

        foreach ((new \ReflectionClass($object))->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if ($property->isStatic()) {
                continue;
            }

            if (!$property->isInitialized($object)) {
                $uninitialized[] = $object::class . '::$' . $property->getName();
                continue;
            }

            $value = $property->getValue($object);

            foreach (is_array($value) ? $value : [$value] as $item) {
                if (is_object($item) && !$item instanceof \UnitEnum) {
                    $this->collectUninitializedProperties($item, $uninitialized, $seen);
                }
            }
        }
    }

    /** @param non-empty-string $format */
    private function deserializePatientWithGender(string $format): PatientResource
    {
        $service = FHIRSerializationService::createDefault();

        $patient = $format === 'xml'
            ? $service->deserializeFromXml(self::PATIENT_WITH_GENDER_XML, PatientResource::class)
            : $service->deserializeFromJson(self::PATIENT_WITH_GENDER_JSON, PatientResource::class);

        self::assertInstanceOf(PatientResource::class, $patient);

        return $patient;
    }

    private function deserializeMinimalPatient(): PatientResource
    {
        $patient = FHIRSerializationService::createDefault()
            ->deserializeFromJson(self::MINIMAL_PATIENT_JSON, PatientResource::class);

        self::assertInstanceOf(PatientResource::class, $patient);

        return $patient;
    }
}

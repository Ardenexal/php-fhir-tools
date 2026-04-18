<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Metadata\Contract\FHIRExtensionInterface;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Verifies that extension and sub-extension arrays in FHIR JSON are deserialized
 * into typed Extension objects rather than kept as plain PHP arrays.
 *
 * Covers the three normalizer paths that were previously returning raw arrays:
 *   - FHIRResourceNormalizer  (top-level resource extension)
 *   - FHIRComplexTypeNormalizer (extensions nested inside complex types like Identifier)
 *   - FHIRBackboneElementNormalizer (extensions nested inside backbone elements)
 */
#[CoversClass(FHIRSerializationService::class)]
final class PatientExtensionDeserializationTest extends TestCase
{
    private FHIRSerializationService $service;

    /** AU Base Patient example with top-level and sub-type extensions. */
    private const PATIENT_JSON = <<<'JSON'
{
  "resourceType": "Patient",
  "id": "example0",
  "extension": [
    {
      "url": "http://hl7.org.au/fhir/StructureDefinition/indigenous-status",
      "valueCoding": {
        "system": "https://healthterminologies.gov.au/fhir/CodeSystem/australian-indigenous-status-1",
        "code": "9",
        "display": "Not stated/inadequately described"
      }
    },
    {
      "url": "http://hl7.org/fhir/StructureDefinition/individual-genderIdentity",
      "extension": [
        {
          "url": "value",
          "valueCodeableConcept": {
            "coding": [
              {
                "system": "http://terminology.hl7.org/CodeSystem/data-absent-reason",
                "code": "asked-declined",
                "display": "Asked But Declined"
              }
            ],
            "text": "Prefer not to answer"
          }
        }
      ]
    }
  ],
  "identifier": [
    {
      "extension": [
        {
          "url": "http://hl7.org.au/fhir/StructureDefinition/ihi-status",
          "valueCoding": {
            "system": "https://healthterminologies.gov.au/fhir/CodeSystem/ihi-status-1",
            "code": "active",
            "display": "active"
          }
        },
        {
          "url": "http://hl7.org.au/fhir/StructureDefinition/ihi-record-status",
          "valueCoding": {
            "system": "https://healthterminologies.gov.au/fhir/CodeSystem/ihi-record-status-1",
            "code": "verified",
            "display": "verified"
          }
        }
      ],
      "type": {
        "coding": [
          {
            "system": "http://terminology.hl7.org/CodeSystem/v2-0203",
            "code": "NI",
            "display": "National unique individual identifier"
          }
        ],
        "text": "IHI"
      },
      "system": "http://ns.electronichealth.net.au/id/hi/ihi/1.0",
      "value": "8003608833357361"
    }
  ],
  "name": [
    {
      "use": "official",
      "family": "Franklin",
      "given": ["Stella"]
    }
  ],
  "gender": "female",
  "birthDate": "1985-10-14"
}
JSON;

    protected function setUp(): void
    {
        $this->service = FHIRSerializationService::createDefault();
    }

    /**
     * Top-level resource extensions must be deserialized into Extension objects.
     *
     * FHIRResourceNormalizer (JSON path) was previously returning raw PHP arrays.
     */
    public function testTopLevelResourceExtensionsAreDeserialized(): void
    {
        $patient = $this->service->deserialize(self::PATIENT_JSON);

        $extensions = (new \ReflectionClass($patient))->getProperty('extension')->getValue($patient);

        self::assertIsArray($extensions, 'extension property must be an array');
        self::assertCount(2, $extensions, 'Patient has 2 top-level extensions');

        foreach ($extensions as $i => $ext) {
            self::assertInstanceOf(
                FHIRExtensionInterface::class,
                $ext,
                "extension[$i] must be an Extension object, got " . get_debug_type($ext),
            );
        }
    }

    /**
     * Simple typed extensions must populate the inherited Extension::$value choice property
     * in addition to their named typed property (e.g. $valueCoding).
     *
     * The constructor does this via parent::__construct(value: $this->valueXxx), but
     * newInstanceWithoutConstructor() skips it — so the normalizer must mirror this.
     */
    public function testSimpleExtensionValuePropertyIsPopulated(): void
    {
        $patient = $this->service->deserialize(self::PATIENT_JSON);

        $extensions = (new \ReflectionClass($patient))->getProperty('extension')->getValue($patient);
        self::assertCount(2, $extensions);

        // First extension (indigenous-status) is a simple typed extension with valueCoding.
        $indigenousStatusExt = $extensions[0];
        self::assertInstanceOf(FHIRExtensionInterface::class, $indigenousStatusExt);

        $value = (new \ReflectionClass($indigenousStatusExt))->getProperty('value')->getValue($indigenousStatusExt);
        self::assertNotNull(
            $value,
            'Simple extension $value must be populated during deserialization (mirrors constructor behaviour)',
        );
    }

    /**
     * Nested sub-extensions inside a complex extension must also be Extension objects.
     *
     * The second top-level extension (individual-genderIdentity) contains its own
     * `extension` child array — those must be deserialized too, not left as raw arrays.
     */
    public function testNestedSubExtensionsAreDeserialized(): void
    {
        $patient = $this->service->deserialize(self::PATIENT_JSON);

        $extensions = (new \ReflectionClass($patient))->getProperty('extension')->getValue($patient);

        self::assertCount(2, $extensions);

        $genderIdentityExt = $extensions[1];
        self::assertInstanceOf(FHIRExtensionInterface::class, $genderIdentityExt);

        $subExtensions = (new \ReflectionClass($genderIdentityExt))->getProperty('extension')->getValue($genderIdentityExt);

        self::assertIsArray($subExtensions, 'Nested extension array must be an array');
        self::assertNotEmpty($subExtensions, 'individual-genderIdentity has 1 sub-extension');

        foreach ($subExtensions as $i => $subExt) {
            self::assertInstanceOf(
                FHIRExtensionInterface::class,
                $subExt,
                "extension[1].extension[$i] must be an Extension object, got " . get_debug_type($subExt),
            );
        }
    }

    /**
     * Extensions inside complex types (Identifier) must be deserialized.
     *
     * FHIRComplexTypeNormalizer was previously keeping these as raw PHP arrays.
     */
    public function testComplexTypeSubExtensionsAreDeserialized(): void
    {
        $patient = $this->service->deserialize(self::PATIENT_JSON);

        $identifiers = (new \ReflectionClass($patient))->getProperty('identifier')->getValue($patient);

        self::assertIsArray($identifiers);
        self::assertNotEmpty($identifiers, 'Patient has at least one identifier');

        $firstIdentifier = $identifiers[0];
        $idExtensions    = (new \ReflectionClass($firstIdentifier))->getProperty('extension')->getValue($firstIdentifier);

        self::assertIsArray($idExtensions, 'identifier[0].extension must be an array');
        self::assertCount(2, $idExtensions, 'First identifier has 2 extensions (ihi-status, ihi-record-status)');

        foreach ($idExtensions as $i => $ext) {
            self::assertInstanceOf(
                FHIRExtensionInterface::class,
                $ext,
                "identifier[0].extension[$i] must be an Extension object, got " . get_debug_type($ext),
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Benchmarks\Serialization;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PhpBench\Attributes as Bench;

/**
 * Benchmarks for FHIR JSON serialization and deserialization.
 *
 * Covers single-resource (Patient) and multi-resource (Bundle) round-trips
 * to measure the cost of normalize/denormalize paths.
 */
#[Bench\BeforeMethods(['setUp'])]
class SerializationJsonBench
{
    private FHIRSerializationService $service;

    private PatientResource $patient;

    private string $patientJson;

    private BundleResource $bundle;

    private string $bundleJson;

    public function setUp(): void
    {
        $this->service = FHIRSerializationService::createDefault();

        // Build a Patient with enough data to exercise most property kinds.
        $this->patient = new PatientResource(
            id: 'bench-patient-001',
            active: true,
            name: [
                new HumanName(
                    use: null,
                    family: 'Smith',
                    given: ['John', 'Michael'],
                ),
            ],
        );

        // Deserialize the fixture Bundle once so both bundle benchmarks
        // start from an already-hydrated object graph.
        $fixturePath      = __DIR__ . '/../../src/Component/Serialization/Fixtures/bundle-response-medsallergies.json';
        $this->bundleJson = (string) file_get_contents($fixturePath);
        $this->bundle     = $this->service->deserializeFromJson($this->bundleJson, BundleResource::class);

        // Pre-serialise the patient so the deserialize benchmark starts from a valid JSON string.
        $this->patientJson = $this->service->serializeToJson($this->patient);
    }

    /**
     * Serialize a minimal Patient resource to JSON.
     */
    public function benchJsonSerializePatient(): void
    {
        $this->service->serializeToJson($this->patient);
    }

    /**
     * Deserialize a Patient from JSON.
     */
    public function benchJsonDeserializePatient(): void
    {
        $this->service->deserializeFromJson($this->patientJson, PatientResource::class);
    }

    /**
     * Serialize a Bundle containing multiple resources to JSON.
     */
    public function benchJsonSerializeBundle(): void
    {
        $this->service->serializeToJson($this->bundle);
    }

    /**
     * Deserialize a Bundle (medications + allergies) from JSON.
     */
    public function benchJsonDeserializeBundle(): void
    {
        $this->service->deserializeFromJson($this->bundleJson, BundleResource::class);
    }
}

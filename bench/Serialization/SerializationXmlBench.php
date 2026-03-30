<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Benchmarks\Serialization;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PhpBench\Attributes as Bench;

/**
 * Benchmarks for FHIR XML serialization and deserialization.
 *
 * Uses the Bundle fixture (medications + allergies) which exercises
 * polymorphic resource handling inside the XML encoder.
 */
#[Bench\BeforeMethods(['setUp'])]
class SerializationXmlBench
{
    private FHIRSerializationService $service;

    private BundleResource $bundle;

    private string $bundleXml;

    public function setUp(): void
    {
        $this->service = FHIRSerializationService::createDefault();

        $fixturePath     = __DIR__ . '/../../src/Component/Serialization/Fixtures/bundle-response-medsallergies.xml';
        $this->bundleXml = (string) file_get_contents($fixturePath);
        $this->bundle    = $this->service->deserializeFromXml($this->bundleXml, BundleResource::class);
    }

    /**
     * Serialize a Bundle to XML.
     */
    public function benchXmlSerializeBundle(): void
    {
        $this->service->serializeToXml($this->bundle);
    }

    /**
     * Deserialize a Bundle from XML.
     */
    public function benchXmlDeserializeBundle(): void
    {
        $this->service->deserializeFromXml($this->bundleXml, BundleResource::class);
    }
}

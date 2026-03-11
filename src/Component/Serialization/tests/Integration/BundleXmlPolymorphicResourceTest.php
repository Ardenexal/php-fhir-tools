<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PatientResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\TestCase;

/**
 * Integration test for XML Bundle deserialization with polymorphic resource properties.
 *
 * Tests the fix for issue where abstract ResourceResource was being instantiated
 * instead of resolving concrete resource types from XML element names.
 */
class BundleXmlPolymorphicResourceTest extends TestCase
{
    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->serializer = FHIRSerializationService::createDefault();
    }

    public function testDeserializeBundleXmlWithPatientResource(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<Bundle xmlns="http://hl7.org/fhir">
    <id value="example-bundle"/>
    <type value="collection"/>
    <entry>
        <resource>
            <Patient>
                <id value="example"/>
                <active value="true"/>
                <gender value="male"/>
            </Patient>
        </resource>
    </entry>
</Bundle>
XML;

        $bundle = $this->serializer->deserializeFromXml($xml, BundleResource::class);

        self::assertInstanceOf(BundleResource::class, $bundle);
        self::assertCount(1, $bundle->entry);

        $entry = $bundle->entry[0];
        self::assertNotNull($entry->resource);
        self::assertInstanceOf(PatientResource::class, $entry->resource);

        $patient = $entry->resource;
        self::assertSame('example', $patient->id);
    }

    public function testDeserializeBundleXmlWithNestedBundles(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<Bundle xmlns="http://hl7.org/fhir">
    <id value="outer-bundle"/>
    <type value="collection"/>
    <entry>
        <resource>
            <Bundle>
                <id value="inner-bundle"/>
                <type value="searchset"/>
                <total value="0"/>
            </Bundle>
        </resource>
    </entry>
</Bundle>
XML;

        $bundle = $this->serializer->deserializeFromXml($xml, BundleResource::class);

        self::assertInstanceOf(BundleResource::class, $bundle);
        self::assertCount(1, $bundle->entry);

        $entry = $bundle->entry[0];
        self::assertNotNull($entry->resource);
        self::assertInstanceOf(BundleResource::class, $entry->resource);

        $innerBundle = $entry->resource;
        self::assertSame('inner-bundle', $innerBundle->id);
    }

    public function testDeserializeBundleXmlWithMultipleResourceTypes(): void
    {
        $fixtureFile = __DIR__ . '/../../Fixtures/bundle-response-medsallergies.xml';

        if (!file_exists($fixtureFile)) {
            self::markTestSkipped('Fixture file not found: ' . $fixtureFile);
        }

        $xml = file_get_contents($fixtureFile);
        self::assertNotFalse($xml, 'Failed to read fixture file');

        $bundle = $this->serializer->deserializeFromXml($xml, BundleResource::class);

        self::assertInstanceOf(BundleResource::class, $bundle);
        self::assertSame('bundle-response-medsallergies', $bundle->id);
        self::assertCount(5, $bundle->entry);

        // First entry should contain a Patient resource
        self::assertInstanceOf(PatientResource::class, $bundle->entry[0]->resource);
        self::assertSame('example', $bundle->entry[0]->resource->id);

        // Remaining entries should contain Bundle resources
        for ($i = 1; $i < 5; ++$i) {
            self::assertInstanceOf(BundleResource::class, $bundle->entry[$i]->resource);
        }
    }

    public function testDeserializeBundleXmlWithNoResource(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<Bundle xmlns="http://hl7.org/fhir">
    <id value="example-bundle"/>
    <type value="collection"/>
    <entry>
        <fullUrl value="http://example.com/Patient/1"/>
    </entry>
</Bundle>
XML;

        $bundle = $this->serializer->deserializeFromXml($xml, BundleResource::class);

        self::assertInstanceOf(BundleResource::class, $bundle);
        self::assertCount(1, $bundle->entry);

        $entry = $bundle->entry[0];
        self::assertNull($entry->resource);
    }
}

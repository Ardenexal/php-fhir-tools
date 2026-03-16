<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Models\R4\Resource\BundleResource;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

/**
 * Tests bidirectional conversion of FHIR Bundle with polymorphic resources.
 *
 * Verifies that Bundle resources with polymorphic entry.resource properties can be:
 * 1. Deserialized from XML and re-serialized to JSON (XML→JSON)
 * 2. Deserialized from JSON and re-serialized to XML (JSON→XML)
 *
 * The polymorphic resource fix enables proper handling of:
 * - Bundle.entry.resource containing any FHIR resource type (Patient, Bundle, etc.)
 * - Nested Bundles within Bundles
 * - Complex Patient data with extensions
 * - Primitive extensions (_birthDate, _family)
 *
 * Background: The original bug caused XML deserialization to fail with
 * "Cannot instantiate abstract class ResourceResource" because it tried to
 * instantiate the base class instead of resolving to concrete types.
 */
class BundleXmlToJsonConversionTest extends TestCase
{
    private FHIRSerializationService $serializer;

    protected function setUp(): void
    {
        $this->serializer = FHIRSerializationService::createDefault();
    }

    public function testXmlToJsonConversionMatchesExpectedStructure(): void
    {
        $xml = file_get_contents(__DIR__ . '/../../Fixtures/bundle-response-medsallergies.xml');
        self::assertNotFalse($xml, 'Failed to read fixture file');

        // Deserialize XML to object
        $bundle = $this->serializer->deserializeFromXml($xml, BundleResource::class);
        self::assertInstanceOf(BundleResource::class, $bundle);

        // Serialize back to JSON
        $actualJson = $this->serializer->serializeToJson($bundle);
        self::assertIsString($actualJson);

        // Parse actual JSON
        $actual = json_decode($actualJson, true);
        self::assertIsArray($actual, 'Failed to decode actual JSON');

        // Validate top-level structure
        self::assertSame('Bundle', $actual['resourceType']);
        self::assertSame('bundle-response-medsallergies', $actual['id']);
        self::assertSame('batch-response', $actual['type']);
        self::assertArrayHasKey('entry', $actual);
        self::assertCount(5, $actual['entry']);

        // Validate first entry (Patient resource) - THE MAIN FIX WE'RE TESTING
        $firstEntry = $actual['entry'][0];
        self::assertArrayHasKey('resource', $firstEntry);
        self::assertSame('Patient', $firstEntry['resource']['resourceType']);
        self::assertSame('example', $firstEntry['resource']['id']);

        // Validate Patient metadata
        self::assertArrayHasKey('meta', $firstEntry['resource']);
        self::assertSame('1', $firstEntry['resource']['meta']['versionId']);
        $this->assertDateTimeEquivalent('2018-11-12T03:35:20.715Z', $firstEntry['resource']['meta']['lastUpdated']);

        // Validate Patient has key fields
        self::assertTrue($firstEntry['resource']['active']);
        self::assertSame('male', $firstEntry['resource']['gender']);
        self::assertSame('1974-12-25', $firstEntry['resource']['birthDate']);

        // Validate primitive extension on birthDate exists
        self::assertArrayHasKey('_birthDate', $firstEntry['resource']);
        self::assertArrayHasKey('extension', $firstEntry['resource']['_birthDate']);
        self::assertNotEmpty($firstEntry['resource']['_birthDate']['extension']);

        // Validate Patient has names
        self::assertArrayHasKey('name', $firstEntry['resource']);
        self::assertNotEmpty($firstEntry['resource']['name']);

        // Validate Patient has identifiers
        self::assertArrayHasKey('identifier', $firstEntry['resource']);
        self::assertNotEmpty($firstEntry['resource']['identifier']);

        // TODO: FIX SERIALIZATION BUG - identifier.type.coding structure is incorrect
        // Currently fails because:
        // 1. coding is an object instead of an array
        // 2. @value wrappers leak from XML internal representation
        // Expected structure:
        //   "type": { "coding": [{ "system": "http://...", "code": "MR" }] }
        // Actual structure:
        //   "type": { "coding": { "system": { "@value": "http://..." }, "code": { "@value": "MR" } } }
        $identifier = $firstEntry['resource']['identifier'][0];
        self::assertArrayHasKey('type', $identifier, 'Identifier should have type');
        self::assertArrayHasKey('coding', $identifier['type'], 'Identifier type should have coding');

        // KNOWN BUG: This test documents the serialization issue
        // Uncomment these assertions once the bug is fixed:
        /*
        self::assertIsArray($identifier['type']['coding'], 'Identifier type.coding should be an array');
        self::assertNotEmpty($identifier['type']['coding'], 'Identifier type.coding should not be empty');

        $coding = $identifier['type']['coding'][0];
        self::assertIsArray($coding, 'Coding should be an array item');
        self::assertArrayHasKey('system', $coding, 'Coding should have system');
        self::assertArrayHasKey('code', $coding, 'Coding should have code');
        self::assertSame('http://terminology.hl7.org/CodeSystem/v2-0203', $coding['system']);
        self::assertSame('MR', $coding['code']);
        self::assertArrayNotHasKey('@value', $coding, 'Coding should not have @value wrapper');
        */

        // Validate entry response
        self::assertArrayHasKey('response', $firstEntry);
        self::assertSame('200', $firstEntry['response']['status']);
        self::assertSame('W/1', $firstEntry['response']['etag']);

        // Validate remaining entries (nested Bundles) - ALSO TESTING POLYMORPHIC RESOURCES
        for ($i = 1; $i < 5; ++$i) {
            $entry = $actual['entry'][$i];
            self::assertArrayHasKey('resource', $entry, "Entry {$i} should have a resource");
            self::assertSame('Bundle', $entry['resource']['resourceType'], "Entry {$i} resource should be a Bundle");
            self::assertSame('searchset', $entry['resource']['type']);
            self::assertSame(0, $entry['resource']['total']);
            self::assertArrayHasKey('link', $entry['resource']);
            self::assertNotEmpty($entry['resource']['link']);

            // Validate response for each entry
            self::assertArrayHasKey('response', $entry);
            self::assertSame('200', $entry['response']['status']);
            self::assertSame('W/1', $entry['response']['etag']);
        }

        // Validate specific Bundle IDs
        self::assertSame('5bdf95d0-24a6-4024-95f5-d546fb479b', $actual['entry'][1]['resource']['id']);
        self::assertSame('0c11a91c-3638-4d58-8cf1-40e60f43c6', $actual['entry'][2]['resource']['id']);
        self::assertSame('19f0fa29-f8fe-4b07-b035-f488893f06', $actual['entry'][3]['resource']['id']);
        self::assertSame('dff8ab42-33f9-42ec-88c5-83d3f05323', $actual['entry'][4]['resource']['id']);

        // SUCCESS: The main purpose of this test is to verify that:
        // 1. XML with polymorphic resources (Bundle.entry.resource) deserializes correctly
        // 2. The deserialized objects can be serialized back to valid FHIR JSON
        // 3. The JSON contains all expected resources with correct resourceType fields
    }

    public function testJsonToXmlConversionPreservesPolymorphicResources(): void
    {
        $json = $this->getExpectedJson();

        // Deserialize JSON to object
        $bundle = $this->serializer->deserializeFromJson($json, BundleResource::class);
        self::assertInstanceOf(BundleResource::class, $bundle);

        // Serialize back to XML
        $actualXml = $this->serializer->serializeToXml($bundle);
        self::assertIsString($actualXml);

        // Parse XML to validate structure
        $xml = simplexml_load_string($actualXml, 'SimpleXMLElement', LIBXML_NONET);
        self::assertNotFalse($xml, 'Failed to parse generated XML');

        // Validate root Bundle element
        self::assertSame('Bundle', $xml->getName());

        // Validate we have 5 entries
        $entries = $xml->entry;
        self::assertCount(5, $entries, 'Bundle should have 5 entries');

        // Validate first entry contains Patient resource data wrapped with <Patient> element
        $firstEntry = $entries[0];
        self::assertNotNull($firstEntry->resource, 'First entry should have a resource element');

        // With the fix, Patient fields are wrapped: <resource><Patient>...</Patient></resource>
        $patient = $firstEntry->resource->Patient;
        self::assertNotNull($patient, 'First entry resource should have a Patient wrapper element');
        self::assertNotNull($patient->id, 'Patient should have id');
        self::assertSame('example', (string) $patient->id['value'], 'Patient ID should match');
        self::assertNotNull($patient->meta, 'Patient should have meta');
        self::assertNotNull($patient->active, 'Patient should have active field');
        self::assertNotNull($patient->gender, 'Patient should have gender');
        self::assertNotNull($patient->birthDate, 'Patient should have birthDate');
        self::assertNotNull($patient->name, 'Patient should have name');
        self::assertNotNull($patient->identifier, 'Patient should have identifier');

        // Validate first entry response
        self::assertNotNull($firstEntry->response, 'First entry should have response');

        // Validate remaining entries contain nested Bundle resource data wrapped with <Bundle> element
        for ($i = 1; $i < 5; ++$i) {
            $entry = $entries[$i];
            self::assertNotNull($entry->resource, "Entry {$i} should have a resource element");

            // With the fix, Bundle fields are wrapped: <resource><Bundle>...</Bundle></resource>
            $bundleResource = $entry->resource->Bundle;
            self::assertNotNull($bundleResource, "Entry {$i} resource should have a Bundle wrapper element");
            self::assertNotNull($bundleResource->id, "Bundle {$i} should have id");
            self::assertNotNull($bundleResource->meta, "Bundle {$i} should have meta");
            self::assertNotNull($bundleResource->type, "Bundle {$i} should have type");
            self::assertNotNull($bundleResource->total, "Bundle {$i} should have total");
            self::assertNotNull($bundleResource->link, "Bundle {$i} should have link");

            // Validate response
            self::assertNotNull($entry->response, "Entry {$i} should have response");
        }

        // SUCCESS: The main purpose of this test is to verify that:
        // 1. JSON with polymorphic resources (Bundle.entry.resource) deserializes correctly
        //    - JSON has "resourceType": "Patient" → deserializes to PatientResource object
        //    - JSON has "resourceType": "Bundle" → deserializes to BundleResource object
        // 2. The deserialized polymorphic objects can be serialized back to valid FHIR XML
        // 3. The XML wraps each resource with its type element: <resource><Patient>...</Patient></resource>
        // 4. Round-trip conversion works (JSON→Object→XML)
    }

    public function testNarrativeDivHasXhtmlNamespace(): void
    {
        $json = $this->getExpectedJson();

        $bundle = $this->serializer->deserializeFromJson($json, BundleResource::class);
        self::assertInstanceOf(BundleResource::class, $bundle);

        $actualXml = $this->serializer->serializeToXml($bundle);
        self::assertIsString($actualXml);

        // Parse with namespace awareness — registerXPathNamespace is needed for namespace-aware queries
        $xml = simplexml_load_string($actualXml, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NSCLEAN);
        self::assertNotFalse($xml, 'Failed to parse generated XML');

        // Navigate to Patient.text.div
        $patient = $xml->entry[0]->resource->Patient;
        self::assertNotNull($patient, 'First entry should have a Patient element');

        $textElement = $patient->text;
        self::assertNotNull($textElement, 'Patient should have a text element');

        $divElement = $textElement->div;
        self::assertNotNull($divElement, 'Narrative text should have a div element');

        // Verify the div carries xmlns="http://www.w3.org/1999/xhtml"
        $namespaces = $divElement->getNamespaces(false);
        self::assertArrayHasKey('', $namespaces, 'div should declare a default namespace');
        self::assertSame(
            'http://www.w3.org/1999/xhtml',
            $namespaces[''],
            'div default namespace must be XHTML, not FHIR',
        );
    }

    /**
     * Test that identifier type.coding is correctly serialized as an array.
     *
     * KNOWN BUG: This test currently fails due to a serialization bug where:
     * 1. CodeableConcept.coding is serialized as an object instead of an array
     * 2. Primitive values have @value wrappers leak from XML internal representation
     *
     * Expected JSON structure:
     *   "type": { "coding": [{ "system": "http://...", "code": "MR" }] }
     *
     * Actual (incorrect) structure:
     *   "type": { "coding": { "system": { "@value": "http://..." }, "code": { "@value": "MR" } } }
     *
     */
    public function testIdentifierTypeCodingIsArrayNotObject(): void
    {

        $xml = file_get_contents(__DIR__ . '/../../Fixtures/bundle-response-medsallergies.xml');
        self::assertNotFalse($xml);

        $bundle = $this->serializer->deserializeFromXml($xml, BundleResource::class);
        $json   = $this->serializer->serializeToJson($bundle);
        $data   = json_decode($json, true);

        $identifier = $data['entry'][0]['resource']['identifier'][0];

        // Coding should be an array, not an object
        self::assertIsArray($identifier['type']['coding'], 'coding should be an array');
        self::assertCount(1, $identifier['type']['coding'], 'coding array should have 1 element');

        $coding = $identifier['type']['coding'][0];

        // Values should be strings, not objects with @value
        self::assertIsString($coding['system'], 'system should be a string');
        self::assertIsString($coding['code'], 'code should be a string');
        self::assertSame('http://terminology.hl7.org/CodeSystem/v2-0203', $coding['system']);
        self::assertSame('MR', $coding['code']);

        // No @value wrappers should be present
        self::assertArrayNotHasKey('@value', $coding);
        if (isset($coding['system'])) {
            self::assertIsString($coding['system'], 'system should not be an object with @value');
        }
        if (isset($coding['code'])) {
            self::assertIsString($coding['code'], 'code should not be an object with @value');
        }
    }

    /**
     * Assert that two DateTime strings represent the same instant in time.
     *
     * Normalizes both to UTC timestamps for comparison, allowing for format differences.
     */
    private function assertDateTimeEquivalent(string $expected, string $actual): void
    {
        try {
            $expectedDt = new \DateTime($expected);
            $actualDt   = new \DateTime($actual);

            self::assertEquals(
                $expectedDt->getTimestamp(),
                $actualDt->getTimestamp(),
                sprintf(
                    'DateTime mismatch: expected "%s" (%s) but got "%s" (%s)',
                    $expected,
                    $expectedDt->format('c'),
                    $actual,
                    $actualDt->format('c'),
                ),
            );
        } catch (\Exception $e) {
            self::fail(sprintf('Failed to parse DateTime: %s', $e->getMessage()));
        }
    }

    /**
     * Get the expected JSON output for conversion tests.
     */
    private function getExpectedJson(): string
    {
        $fixturePath = __DIR__ . '/../../Fixtures/bundle-response-medsallergies.json';
        $json        = file_get_contents($fixturePath);

        if ($json === false) {
            throw new \RuntimeException("Failed to load fixture: {$fixturePath}");
        }

        return $json;
    }
}

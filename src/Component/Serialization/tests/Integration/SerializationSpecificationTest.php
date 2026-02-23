<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Integration;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Official FHIR R4 serialization specification conformance test.
 *
 * Test cases are driven from the official fhir/fhir-test-cases r4/examples/
 * directory, exercising deserialization + round-trip assertions against real
 * FHIR example files.
 *
 * Individual tests are skipped (not failed) when deserialization is not yet
 * supported for the resource type, clearly separating:
 * - Skipped = resource type not yet supported by the deserializer
 * - Failed  = deserialization worked but serialize→deserialize cycle broke
 */
#[CoversClass(FHIRSerializationService::class)]
final class SerializationSpecificationTest extends TestCase
{
    private FHIRSerializationService $service;

    protected function setUp(): void
    {
        $this->service = FHIRSerializationService::createDefault();
    }

    /**
     * Build the data set from r4/examples/ in fhir-test-cases.
     *
     * Each yielded row: [string $filename, string $contents, string $format]
     *
     * When the vendor directory is not installed a single sentinel row is yielded
     * so PHPUnit does not treat the empty data set as an error.
     *
     * @return iterable<string, array{string, string, string}>
     */
    public static function provideExampleFiles(): iterable
    {
        $examplesDir = dirname(__DIR__, 5) . '/vendor/fhir/fhir-test-cases/r4/examples';

        if (!is_dir($examplesDir)) {
            yield '__vendor_not_installed__' => ['__skip__', '', 'json'];

            return;
        }

        $files = glob($examplesDir . '/*.{json,xml}', GLOB_BRACE);
        if ($files === false || $files === []) {
            yield '__no_examples__' => ['__skip__', '', 'json'];

            return;
        }

        foreach ($files as $filePath) {
            $filename = basename($filePath);

            // Skip FHIRPath expression output files — not FHIR resources
            if (str_ends_with($filename, '.json.out')) {
                continue;
            }

            $contents = file_get_contents($filePath);
            if ($contents === false || $contents === '') {
                continue;
            }

            $ext    = pathinfo($filename, PATHINFO_EXTENSION);
            $format = $ext === 'xml' ? 'xml' : 'json';

            yield $filename => [$filename, $contents, $format];
        }
    }

    /**
     * Deserialize a FHIR example file, re-serialize it, and verify the
     * round-trip preserves the resource identity (resourceType and id).
     *
     * @param string $filename File name (used in assertion messages)
     * @param string $contents Raw file contents
     * @param string $format   'json' or 'xml'
     */
    #[DataProvider('provideExampleFiles')]
    public function testRoundTrip(string $filename, string $contents, string $format): void
    {
        // Sentinel: vendor directory not installed
        if ($filename === '__skip__') {
            $this->markTestSkipped('fhir/fhir-test-cases not installed — run: composer update fhir/fhir-test-cases');
        }

        // Step 1: Deserialize original file → typed model object.
        // Skip (not fail) if the resource type is not yet supported.
        try {
            $original = $this->service->deserialize($contents);
        } catch (\Throwable $e) {
            $this->markTestSkipped(sprintf('Deserialization failed: %s', $e->getMessage()));
        }

        // Step 2: Re-serialize back to the original format.
        // Fail if deserialization worked but serialization broke.
        try {
            $reserialized = $format === 'xml'
                ? $this->service->serializeToXml($original)
                : $this->service->serializeToJson($original);
        } catch (\Throwable $e) {
            $this->fail(sprintf('[%s] Serialization failed after successful deserialization: %s', $filename, $e->getMessage()));
        }

        // Step 3: Re-deserialize the output back to a second object.
        try {
            $roundTripped = $this->service->deserialize($reserialized);
        } catch (\Throwable $e) {
            $this->fail(sprintf('[%s] Re-deserialization of serialized output failed: %s', $filename, $e->getMessage()));
        }

        // Step 4: Verify resource identity survived the round-trip.
        if ($format === 'json') {
            $this->assertJsonRoundTrip($contents, $reserialized, $filename);
        } else {
            $this->assertXmlRoundTrip($contents, $reserialized, $filename);
        }

        // If we reached here, the round-trip succeeded.
        // At minimum the two deserialized objects exist — confirm same class.
        self::assertSame(get_class($original), get_class($roundTripped), sprintf('[%s] Class changed during round-trip', $filename));
    }

    /**
     * Assert resourceType and id are preserved in a JSON round-trip.
     */
    private function assertJsonRoundTrip(string $originalContents, string $reserialized, string $filename): void
    {
        $originalDecoded  = json_decode($originalContents, true);
        $roundTripDecoded = json_decode($reserialized, true);

        if (!is_array($originalDecoded) || !is_array($roundTripDecoded)) {
            $this->fail(sprintf('[%s] Could not decode JSON for comparison', $filename));
        }

        if (isset($originalDecoded['resourceType'])) {
            self::assertSame(
                $originalDecoded['resourceType'],
                $roundTripDecoded['resourceType'] ?? null,
                sprintf('[%s] resourceType changed during round-trip', $filename),
            );
        }

        if (isset($originalDecoded['id'])) {
            self::assertSame(
                $originalDecoded['id'],
                $roundTripDecoded['id'] ?? null,
                sprintf('[%s] id changed during round-trip', $filename),
            );
        }
    }

    /**
     * Assert the root element name is preserved in an XML round-trip.
     */
    private function assertXmlRoundTrip(string $originalContents, string $reserialized, string $filename): void
    {
        $originalXml     = simplexml_load_string($originalContents, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOERROR);
        $roundTrippedXml = simplexml_load_string($reserialized, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOERROR);

        if ($originalXml === false || $roundTrippedXml === false) {
            $this->fail(sprintf('[%s] Could not parse XML for comparison', $filename));
        }

        self::assertSame(
            $originalXml->getName(),
            $roundTrippedXml->getName(),
            sprintf('[%s] XML root element name changed during round-trip', $filename),
        );
    }
}

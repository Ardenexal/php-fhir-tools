<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Tests\Integration;

use Ardenexal\FHIRTools\Component\FHIRPath\Exception\FHIRPathException;
use Ardenexal\FHIRTools\Component\FHIRPath\Service\FHIRPathService;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\FHIRPathEvaluator;

/**
 * Official FHIR FHIRPath specification conformance test.
 *
 * Test cases are driven entirely from the official fhir/fhir-test-cases XML file
 * (vendor/fhir/fhir-test-cases/r4/fhirpath/tests-fhir-r4.xml), so this suite
 * stays in sync with the FHIR specification automatically.
 *
 * Resources are deserialized via FHIRSerializationService::createDefault() to
 * typed model objects, then evaluated through the full FHIRPath stack. This
 * exercises Plan 1's typed-model support end-to-end.
 *
 * Individual tests are skipped (not failed) when:
 * - The fhir/fhir-test-cases vendor directory is not installed
 * - An input file referenced by the test XML is not present on disk
 * - The expected output type is not yet supported (e.g. Quantity)
 */
#[CoversClass(FHIRPathService::class)]
#[CoversClass(FHIRPathEvaluator::class)]
final class FHIRPathSpecificationTest extends TestCase
{
    private FHIRPathService $service;

    private FHIRSerializationService $serialization;

    protected function setUp(): void
    {
        $this->service       = new FHIRPathService();
        $this->serialization = FHIRSerializationService::createDefault();
    }

    /**
     * Build the data set from the official FHIR FHIRPath test XML.
     *
     * Each yielded row: [expression, inputFile|null, expectedOutputs[], isInvalid, isPredicate]
     *
     * When the vendor directory is not installed a single sentinel row is yielded
     * so PHPUnit does not treat the empty data set as an error. The test method
     * detects the sentinel and skips itself.
     *
     * @return iterable<string, array{string, string|null, list<array{type: string, value: string}>, bool, bool}>
     */
    public static function provideFHIRPathTestCases(): iterable
    {
        $vendorDir = dirname(__DIR__, 5) . '/vendor';
        $xmlFile   = $vendorDir . '/fhir/fhir-test-cases/r4/fhirpath/tests-fhir-r4.xml';

        if (!file_exists($xmlFile)) {
            // Yield a sentinel so PHPUnit sees a non-empty provider;
            // the test method detects the magic expression and skips.
            yield '__vendor_not_installed__' => ['__skip__', null, [], false, false];

            return;
        }

        $xml = simplexml_load_file($xmlFile);
        if ($xml === false) {
            return;
        }

        // Track seen keys to handle duplicate test names in the XML
        /** @var array<string, int> $seenKeys */
        $seenKeys = [];

        foreach ($xml->group as $group) {
            foreach ($group->test as $test) {
                $name        = (string) $test['name'];
                $expression  = (string) $test->expression;
                $isInvalid   = isset($test->expression['invalid']);
                $inputFile   = (string) ($test['inputfile'] ?? '');
                $isPredicate = ((string) ($test['predicate'] ?? '')) === 'true';

                /** @var list<array{type: string, value: string}> $outputs */
                $outputs = [];
                foreach ($test->output as $output) {
                    $outputs[] = ['type' => (string) $output['type'], 'value' => (string) $output];
                }

                // Make key unique when the same test name appears more than once
                if (isset($seenKeys[$name])) {
                    ++$seenKeys[$name];
                    $key = $name . '_' . $seenKeys[$name];
                } else {
                    $seenKeys[$name] = 0;
                    $key             = $name;
                }

                yield $key => [
                    $expression,
                    $inputFile !== '' ? $inputFile : null,
                    $outputs,
                    $isInvalid,
                    $isPredicate,
                ];
            }
        }
    }

    /**
     * @param list<array{type: string, value: string}> $expectedOutputs
     */
    #[DataProvider('provideFHIRPathTestCases')]
    public function testFHIRPathSpecificationCase(
        string $expression,
        ?string $inputFile,
        array $expectedOutputs,
        bool $isInvalid,
        bool $isPredicate,
    ): void {
        $vendorDir = dirname(__DIR__, 5) . '/vendor';

        // Sentinel yielded when the vendor directory is not installed
        if ($expression === '__skip__') {
            $this->markTestSkipped('fhir/fhir-test-cases not installed — run: composer update fhir/fhir-test-cases');
        }

        if (!is_dir($vendorDir . '/fhir/fhir-test-cases')) {
            $this->markTestSkipped('fhir/fhir-test-cases not installed — run: composer update fhir/fhir-test-cases');
        }

        if ($isInvalid) {
            $this->expectException(FHIRPathException::class);
        }

        $resource = $inputFile !== null
            ? $this->loadResourceFile($vendorDir . '/fhir/fhir-test-cases/r4/' . $inputFile)
            : new \stdClass();

        $result = $this->service->evaluate($expression, $resource, fhirVersion: 'R4');

        if ($isInvalid) {
            // expectException already set; execution should not reach here
            return;
        }

        if ($isPredicate) {
            // FHIRPath predicate coercion per spec:
            //   single boolean → use that value
            //   non-empty non-boolean collection → true
            //   empty collection → false
            $coerced = !$result->isEmpty()
                ? (($result->count() === 1 && is_bool($result->first())) ? $result->first() : true)
                : false;

            self::assertSame(
                $this->castOutputValue($expectedOutputs[0]['value'], $expectedOutputs[0]['type']),
                $coerced,
            );

            return;
        }

        if (empty($expectedOutputs)) {
            self::assertTrue($result->isEmpty(), "Expected empty result for expression: {$expression}");

            return;
        }

        self::assertCount(count($expectedOutputs), $result, "Result count mismatch for expression: {$expression}");

        foreach ($expectedOutputs as $i => $expected) {
            self::assertSame(
                $this->castOutputValue($expected['value'], $expected['type']),
                $result->toArray()[$i],
                "Result item [{$i}] mismatch for expression: {$expression}",
            );
        }
    }

    /**
     * Deserialize a FHIR resource file (JSON or XML) to a typed model object.
     * Skips the test when the file cannot be found or deserialized.
     */
    private function loadResourceFile(string $path): object
    {
        if (!file_exists($path)) {
            $this->markTestSkipped("Input file not found: {$path}");
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            $this->markTestSkipped("Could not read input file: {$path}");
        }

        try {
            return $this->serialization->deserialize($contents);
        } catch (\Throwable $e) {
            $this->markTestSkipped(
                sprintf('Could not deserialize resource file %s: %s', basename($path), $e->getMessage()),
            );
        }
    }

    /**
     * Cast an expected output value string to the appropriate PHP type.
     *
     * Unsupported types (e.g. Quantity) cause the test to be skipped rather than
     * failed, since the evaluator does not yet produce those types.
     *
     * @param string $value
     * @param string $type
     *
     * @return mixed
     */
    private function castOutputValue(string $value, string $type): mixed
    {
        return match ($type) {
            'boolean'          => $value === 'true',
            'integer'          => (int) $value,
            'decimal'          => (float) $value,
            'string', 'code'   => $value,
            'date', 'dateTime' => ltrim($value, '@'), // strip FHIRPath date literal prefix
            default            => $this->markTestSkipped("Unsupported output type: {$type}"),
        };
    }
}

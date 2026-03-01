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
use Ardenexal\FHIRTools\Component\FHIRPath\Type\FHIRPathDecimal;

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
            $actual = $result->toArray()[$i];
            if ($actual instanceof FHIRPathDecimal) {
                $actual = $actual->toFloat();
            }

            $expectedType  = $expected['type'];
            $expectedValue = $expected['value'];

            // Handle Quantity array output (e.g. from lowBoundary/highBoundary on a Quantity
            // input). A Quantity result is an array with 'value' (float) and 'unit' (string).
            // Expected format: "1.58650000 'cm'" — compare value and unit separately.
            if (is_array($actual) && array_key_exists('value', $actual) && array_key_exists('unit', $actual)) {
                if (preg_match("/^(-?[\d.]+(?:[eE][+-]?\d+)?)\s+'([^']+)'$/", $expectedValue, $m)) {
                    $qtyActualValue = $actual['value'];
                    self::assertSame(
                        (float) $m[1],
                        is_float($qtyActualValue) ? $qtyActualValue : (float) $qtyActualValue,
                        "Quantity value mismatch [{$i}] for expression: {$expression}",
                    );
                    self::assertSame(
                        $m[2],
                        (string) $actual['unit'],
                        "Quantity unit mismatch [{$i}] for expression: {$expression}",
                    );
                } else {
                    $this->markTestSkipped("Cannot compare Quantity output for expression: {$expression}");
                }
                continue;
            }

            // Infer expected type from the actual result type when the XML provides no type
            // attribute. This covers functions like lowBoundary, highBoundary, precision, and
            // comparable whose <output> elements omit the type attribute in the FHIR test XML.
            if ($expectedType === '') {
                if (is_bool($actual)) {
                    $expectedType = 'boolean';
                } elseif (is_float($actual)) {
                    $expectedType = 'decimal';
                } elseif (is_int($actual)) {
                    $expectedType = 'integer';
                } elseif (is_string($actual)) {
                    if (str_starts_with($expectedValue, '@T')) {
                        $expectedType = 'time';
                    } elseif (str_starts_with($expectedValue, '@')) {
                        $expectedType = 'dateTime'; // covers both date and dateTime (stripped identically)
                    } elseif (str_contains($expectedValue, "'")) {
                        // Quantity output (e.g. "1.58650000 'cm'") — not yet supported
                        $this->markTestSkipped("Quantity output not yet supported: {$expectedValue}");
                    } else {
                        $expectedType = 'string';
                    }
                } else {
                    $this->markTestSkipped("Cannot compare non-scalar output for expression: {$expression}");
                }
            }

            self::assertSame(
                $this->castOutputValue($expectedValue, $expectedType),
                $actual,
                "Result item [{$i}] mismatch for expression: {$expression}",
            );
        }
    }

    /**
     * Deserialize a FHIR resource file (JSON or XML) to a typed model object.
     * Skips the test when the file cannot be found.
     */
    private function loadResourceFile(string $path): object
    {
        if (!file_exists($path)) {
            $this->markTestSkipped("Input file not found: {$path}");
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            self::fail("Could not read input file: {$path}");
        }

        return $this->serialization->deserialize($contents);
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
            'date', 'dateTime', 'time' => ltrim($value, '@'), // strip FHIRPath date/time literal prefix
            default            => $this->markTestSkipped("Unsupported output type: {$type}"),
        };
    }
}

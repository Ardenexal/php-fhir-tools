<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Operation;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationParameterMapper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Round-trips the specification's own published `$lookup` response examples: wire → typed → wire.
 *
 * Every other test in this directory starts from a typed object and ends at one. That direction can
 * pass while the mapper is unable to read anything a real server sends, because the fixture and the
 * assertion share the same author. These start from bytes copied verbatim off
 * `hl7.org/fhir/R4/codesystem-operation-lookup.html` and the R5 equivalent.
 *
 * ## Round-trip equality is structural, not byte-identical
 *
 * Byte identity is unachievable and, worse, tautological — an identity function satisfies it.
 * Equality here means: decode both sides, compare recursively, object keys order-insensitive, list
 * order significant (`Parameters.parameter` is ordered on the wire), and absent distinguished from
 * null. So the round trip is only half the evidence; the typed intermediate is asserted separately.
 *
 * ## The published examples are defective, and that is load-bearing
 *
 * Both examples emit a parameter named `abstract`, which **no version's `$lookup`
 * OperationDefinition declares** — it is a leftover from STU3. `testPublishedExampleDeclaresA...`
 * proves that against the definition rather than asserting it in prose. The consequence is that the
 * examples cannot round-trip cleanly for a reason that is the spec's, not the mapper's, so the
 * expectation here is an exactly-one-difference assertion rather than equality.
 *
 * That surfaces a real design question the mapper currently answers by omission: an undeclared
 * parameter is **silently dropped**. Asserted below so the behaviour is deliberate and visible
 * rather than accidental.
 */
final class OperationSpecExampleRoundTripTest extends TestCase
{
    /**
     * The published example emits a parameter its own OperationDefinition does not declare.
     *
     * Evidence, not opinion: the assertion reads both files.
     */
    #[DataProvider('versionProvider')]
    public function testPublishedExampleDeclaresAParameterTheDefinitionDoesNot(
        string $version,
        FhirVersion $fhirVersion,
    ): void {
        $declared = array_column(self::definition($version)['parameter'], 'name');
        $emitted  = array_column(self::specExample($version)['parameter'], 'name');

        self::assertContains('abstract', $emitted, 'The published example no longer emits `abstract`.');
        self::assertNotContains(
            'abstract',
            $declared,
            'The definition now declares `abstract` — this test documented a spec defect that has been fixed.',
        );

        // Everything else the example emits IS declared, so `abstract` is the sole discrepancy and
        // the exactly-one-difference expectation below is justified.
        self::assertSame(
            ['abstract'],
            array_values(array_diff($emitted, $declared)),
            'The published example gained another undeclared parameter.',
        );
    }

    /**
     * The published example round-trips, losing only the parameter the definition never declared.
     */
    #[DataProvider('versionProvider')]
    public function testPublishedExampleRoundTripsApartFromTheUndeclaredParameter(
        string $version,
        FhirVersion $fhirVersion,
    ): void {
        $mapper  = OperationParameterMapper::createDefault($fhirVersion);
        $service = FHIRSerializationService::createDefault($fhirVersion);

        $body     = $service->deserializeFromJson(self::specExampleJson($version), self::parametersClass($version));
        $restored = $mapper->fromParameters($body, self::outputClass($version));

        /** @var array<string, mixed> $emitted */
        $emitted = json_decode(
            $service->serializeToJson($mapper->toParameters($restored)),
            true,
            512,
            \JSON_THROW_ON_ERROR,
        );

        $expected = self::specExample($version);

        // Drop the undeclared parameter from the expectation, then demand exact structural equality.
        // Subtracting it up front is what keeps this a real assertion: everything else must survive.
        $expected['parameter'] = array_values(array_filter(
            $expected['parameter'],
            static fn (array $p): bool => $p['name'] !== 'abstract',
        ));

        self::assertSame(
            [],
            self::structuralDiff($expected, $emitted),
            'The published example did not survive the round trip.',
        );
    }

    /**
     * The undeclared parameter is dropped rather than carried through or crashed on.
     *
     * Documents the mapper's current answer to "what happens to a parameter the class does not
     * declare". Silent tolerance is the right default for reading a response — servers add
     * parameters, and a strict reader would reject conformant-enough bodies — but it is a real
     * data-loss path and should be a deliberate choice, not an accident of iteration order.
     */
    #[DataProvider('versionProvider')]
    public function testUndeclaredParameterIsDroppedNotCarried(string $version, FhirVersion $fhirVersion): void
    {
        $mapper  = OperationParameterMapper::createDefault($fhirVersion);
        $service = FHIRSerializationService::createDefault($fhirVersion);

        $body     = $service->deserializeFromJson(self::specExampleJson($version), self::parametersClass($version));
        $restored = $mapper->fromParameters($body, self::outputClass($version));

        $emitted = array_map(
            static fn (object $p): string => (string) $p->name,
            $mapper->toParameters($restored)->parameter,
        );

        self::assertNotContains('abstract', $emitted);
        self::assertSame(['name', 'version', 'display', 'designation'], $emitted);
    }

    /**
     * The typed intermediate carries the example's real values at the right paths.
     *
     * Round-trip identity alone proves the mapper is *a* bijection, not the *correct* one. This is
     * the half that would fail if the mapper quietly passed the resource through untouched.
     */
    #[DataProvider('versionProvider')]
    public function testRestoredPayloadCarriesTheExamplesValues(string $version, FhirVersion $fhirVersion): void
    {
        $mapper  = OperationParameterMapper::createDefault($fhirVersion);
        $service = FHIRSerializationService::createDefault($fhirVersion);

        $restored = $mapper->fromParameters(
            $service->deserializeFromJson(self::specExampleJson($version), self::parametersClass($version)),
            self::outputClass($version),
        );

        self::assertSame('LOINC', $restored->name);
        self::assertSame('2.48', $restored->version);
        self::assertSame('Bicarbonate [Moles/volume] in Serum', $restored->display);

        // `designation` is max:'*', so it must be an array even though the example carries one entry.
        self::assertIsArray($restored->designation);
        self::assertCount(1, $restored->designation);
        self::assertSame('Bicarbonate [Moles/volume] in Serum', $restored->designation[0]->value);

        // Absent optional parts stay absent rather than becoming empty strings.
        self::assertNull($restored->designation[0]->language);
        self::assertNull($restored->designation[0]->use);
        self::assertSame([], $restored->property);
    }

    /**
     * A payload exercising every declared OUT parameter round-trips with zero differences.
     *
     * The published example only touches four of them and never nests past one level. This one
     * covers `property`, `property.subproperty`, both polymorphic `value` slots and a repeated
     * `max: '*'` parameter, and demands exact structural equality with no subtraction.
     */
    #[DataProvider('versionProvider')]
    public function testFullCoveragePayloadRoundTripsWithNoDifferences(string $version, FhirVersion $fhirVersion): void
    {
        $mapper  = OperationParameterMapper::createDefault($fhirVersion);
        $service = FHIRSerializationService::createDefault($fhirVersion);
        $wire    = self::fullCoverageWire();

        $body     = $service->deserializeFromJson($wire, self::parametersClass($version));
        $restored = $mapper->fromParameters($body, self::outputClass($version));

        /** @var array<string, mixed> $emitted */
        $emitted = json_decode(
            $service->serializeToJson($mapper->toParameters($restored)),
            true,
            512,
            \JSON_THROW_ON_ERROR,
        );

        /** @var array<string, mixed> $expected */
        $expected = json_decode($wire, true, 512, \JSON_THROW_ON_ERROR);

        self::assertSame([], self::structuralDiff($expected, $emitted));

        // And the typed intermediate really reached the deepest path.
        self::assertSame('inherited-from', $restored->property[0]->subproperty[0]->value->value);
        self::assertCount(2, $restored->designation);
    }

    /**
     * The XML leg: same payload, same mapper, XML in and out.
     *
     * D2 claims JSON and XML "come free" because the mapper hands a real `Parameters` resource to
     * the serializer and never formats anything itself. This is that claim under test. XML is the
     * harder direction — `value[x]` becomes an element name rather than an object key, and every
     * primitive is an attribute — so a mapper that had quietly grown a JSON assumption fails here.
     */
    #[DataProvider('versionProvider')]
    public function testPayloadRoundTripsThroughXmlAsWellAsJson(string $version, FhirVersion $fhirVersion): void
    {
        $mapper          = OperationParameterMapper::createDefault($fhirVersion);
        $service         = FHIRSerializationService::createDefault($fhirVersion);
        $parametersClass = self::parametersClass($version);

        $original = $mapper->fromParameters(
            $service->deserializeFromJson(self::fullCoverageWire(), $parametersClass),
            self::outputClass($version),
        );

        $xml = $service->serializeToXml($mapper->toParameters($original));

        self::assertStringContainsString('<Parameters', $xml);
        self::assertStringContainsString('<valueCoding>', $xml, 'A complex variant lost its element name in XML.');
        self::assertStringContainsString('valueCode value="inherited-from"', $xml, 'The nested polymorphic value did not reach XML.');

        $restored = $mapper->fromParameters(
            $service->deserializeFromXml($xml, $parametersClass),
            self::outputClass($version),
        );

        self::assertEquals($original, $restored, 'The payload survived JSON but not XML.');

        // The two formats must agree, not merely each be self-consistent: a symmetrical bug in one
        // leg would pass an equality check against itself.
        self::assertSame(
            $service->serializeToJson($mapper->toParameters($original)),
            $service->serializeToJson($mapper->toParameters($restored)),
            'JSON and XML round trips produced different payloads.',
        );
    }

    /**
     * Decimal precision is lost at the `json_decode` boundary, before the mapper sees the value.
     *
     * FHIR requires decimal precision to be preserved, and the generated models carry `decimal` as a
     * **string** for exactly that reason. But `json_decode` has already turned the wire token into a
     * PHP float by the time any of this code runs, so `1.50` arrives as `1.5` and `0.000001` as
     * `1.0E-6`.
     *
     * This is a `Serialization` boundary defect, **not** an operation-mapper one — it reproduces on
     * a plain `Parameters` resource with no operation classes involved, as constructed below. It is
     * recorded here because M01's round-trip equality clause names decimals explicitly, and because
     * a fixture carrying a trailing-zero decimal would otherwise fail for a reason unrelated to what
     * these tests cover. Fixing it means decoding numbers as strings, which is out of M01's scope.
     */
    #[DataProvider('versionProvider')]
    public function testDecimalPrecisionIsLostAtTheJsonBoundary(string $version, FhirVersion $fhirVersion): void
    {
        $service = FHIRSerializationService::createDefault($fhirVersion);

        $wire = '{"resourceType":"Parameters","parameter":[{"name":"p","valueDecimal":1.50}]}';

        $decoded = $service->deserializeFromJson($wire, self::parametersClass($version));

        self::assertSame(
            '1.5',
            $decoded->parameter[0]->value,
            'Decimal handling changed — if precision is now preserved, this test and its execution '
            . 'note should be retired rather than updated.',
        );
    }

    /**
     * @return iterable<string, array{string, FhirVersion}>
     */
    public static function versionProvider(): iterable
    {
        yield 'R4' => ['R4', FhirVersion::R4];
        yield 'R5' => ['R5', FhirVersion::R5];
    }

    /**
     * A wire payload touching every OUT parameter common to R4 and R5, nested to the deepest level.
     *
     * Deliberately restricted to the common subset so one fixture serves both versions: R5's extra
     * `definition`, `designation.additionalUse` and `property.source` are optional, so omitting them
     * is valid in both. Deliberately free of decimals — see
     * {@see testDecimalPrecisionIsLostAtTheJsonBoundary}.
     */
    private static function fullCoverageWire(): string
    {
        return <<<'JSON'
            {
              "resourceType": "Parameters",
              "parameter": [
                { "name": "name", "valueString": "ACME Codes" },
                { "name": "version", "valueString": "2026-01" },
                { "name": "display", "valueString": "Left displacement" },
                {
                  "name": "designation",
                  "part": [
                    { "name": "language", "valueCode": "de" },
                    { "name": "value", "valueString": "Rechtsherzkatheter" }
                  ]
                },
                {
                  "name": "designation",
                  "part": [
                    { "name": "value", "valueString": "Left displacement" }
                  ]
                },
                {
                  "name": "property",
                  "part": [
                    { "name": "code", "valueCode": "parent" },
                    { "name": "value", "valueCoding": { "display": "Parent of" } },
                    { "name": "description", "valueString": "Parent concept" },
                    {
                      "name": "subproperty",
                      "part": [
                        { "name": "code", "valueCode": "inherited" },
                        { "name": "value", "valueCode": "inherited-from" },
                        { "name": "description", "valueString": "Inherited from parent" }
                      ]
                    }
                  ]
                }
              ]
            }
            JSON;
    }

    /**
     * Recursive structural comparison, returning a list of human-readable difference paths.
     *
     * Returning the differences rather than a boolean is deliberate: a failing round trip needs to
     * say *what* moved, and an empty-array assertion prints the whole list on failure.
     *
     * @return list<string>
     */
    private static function structuralDiff(mixed $expected, mixed $actual, string $path = '$'): array
    {
        if (is_array($expected) && is_array($actual)) {
            $expectedIsList = array_is_list($expected);

            if ($expectedIsList !== array_is_list($actual)) {
                return [sprintf('%s: list/object mismatch', $path)];
            }

            // Two empty arrays are equal, and short-circuiting here is load-bearing rather than an
            // optimisation: `range(0, -1)` returns `[0, -1]` in PHP, not an empty list, so the key
            // walk below would report two phantom differences and read past the end of both arrays.
            // No M01 fixture has an empty `part`/`parameter` list; M02's will.
            if ($expected === [] && $actual === []) {
                return [];
            }

            $differences = [];

            // Lists compare positionally — `Parameters.parameter` is ordered on the wire and the
            // repeated-parameter convention for `max: '*'` makes that order meaningful. Objects
            // compare key-insensitively, since JSON object key order carries no meaning.
            $keys = $expectedIsList
                ? range(0, max(count($expected), count($actual)) - 1)
                : array_unique([...array_keys($expected), ...array_keys($actual)]);

            foreach ($keys as $key) {
                $childPath = $expectedIsList ? sprintf('%s[%s]', $path, $key) : sprintf('%s.%s', $path, $key);

                // Absent is distinguished from null: a key present-but-null is a different document
                // from a key that is missing, and FHIR treats them differently.
                if (!array_key_exists($key, $expected)) {
                    $differences[] = sprintf('%s: unexpected (%s)', $childPath, json_encode($actual[$key]));

                    continue;
                }

                if (!array_key_exists($key, $actual)) {
                    $differences[] = sprintf('%s: missing (expected %s)', $childPath, json_encode($expected[$key]));

                    continue;
                }

                $differences = [...$differences, ...self::structuralDiff($expected[$key], $actual[$key], $childPath)];
            }

            return $differences;
        }

        // Decimals are compared as strings — FHIR requires precision preservation, and 1.50 and 1.5
        // are different FHIR decimals even though they are the same PHP float.
        if (is_float($expected) || is_float($actual)) {
            $same = self::decimalString($expected) === self::decimalString($actual);

            return $same ? [] : [sprintf(
                '%s: expected %s, got %s',
                $path,
                self::decimalString($expected),
                self::decimalString($actual),
            )];
        }

        if ($expected === $actual) {
            return [];
        }

        return [sprintf('%s: expected %s, got %s', $path, json_encode($expected), json_encode($actual))];
    }

    private static function decimalString(mixed $value): string
    {
        return is_float($value) || is_int($value)
            ? rtrim(rtrim(sprintf('%.17F', $value), '0'), '.')
            : var_export($value, true);
    }

    /**
     * @return array{parameter: list<array<string, mixed>>}
     */
    private static function specExample(string $version): array
    {
        /** @var array{parameter: list<array<string, mixed>>} $decoded */
        $decoded = json_decode(self::specExampleJson($version), true, 512, \JSON_THROW_ON_ERROR);

        return $decoded;
    }

    private static function specExampleJson(string $version): string
    {
        $file = sprintf(
            '%s/../../Fixtures/SpecExamples/%s-codesystem-lookup-response.json',
            __DIR__,
            strtolower($version),
        );

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Spec example fixture %s is unreadable.', $file));

        return $contents;
    }

    /**
     * @return array{parameter: list<array<string, mixed>>}
     */
    private static function definition(string $version): array
    {
        $file = sprintf(
            '%s/../../Fixtures/OperationDefinitions/%s-CodeSystem-lookup.json',
            __DIR__,
            strtolower($version),
        );

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Definition fixture %s is unreadable.', $file));

        /** @var array{parameter: list<array<string, mixed>>} $decoded */
        $decoded = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);

        return $decoded;
    }

    /**
     * @return class-string
     */
    private static function outputClass(string $version): string
    {
        /** @var class-string */
        return sprintf(
            'Ardenexal\FHIRTools\Component\Serialization\Tests\Fixtures\Operations\%s\CodeSystemLookupOutput',
            $version,
        );
    }

    /**
     * @return class-string
     */
    private static function parametersClass(string $version): string
    {
        /** @var class-string */
        return sprintf('Ardenexal\FHIRTools\Component\Models\%s\Resource\ParametersResource', $version);
    }
}

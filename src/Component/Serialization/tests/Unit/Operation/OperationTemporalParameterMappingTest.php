<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit\Operation;

use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationMappingException;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationParameterMapper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Ardenexal\FHIRTools\Tests\Integration\OperationCrossVersionParityTest;

/**
 * Temporal operation parameters map in both directions.
 *
 * Generated operation payload properties are bare `?string`, but the four temporal primitive wrappers
 * do not take a string: `DateTimePrimitive::$value` is `?FHIRDateTime`. FHIR temporals carry variable
 * precision — `2026`, `2026-08` and `2026-08-07T12:00:00Z` are all valid `dateTime` values — and a
 * plain string cannot record which precision was meant, so the models use a value object.
 *
 * Until this was fixed the mapper did `new $wrapper(value: $item)` with the raw string, so **every**
 * temporal parameter raised a `TypeError` on the wrapper constructor instead of mapping. That is 22
 * parameters in R4, 22 in R4B and 28 in R5 — `CodeSystem/$lookup.date`, `CodeSystem/$validate-code.date`,
 * every `$everything` operation's `_since`, `Group/$everything`'s `start` and `end` — none of them
 * usable.
 *
 * It went unnoticed because M01 and M02's fixtures populate only `code`, `string` and `Coding` values.
 * The whole 154-operation fidelity check passes without ever constructing a temporal parameter: it
 * compares *declared metadata* against the published definitions, not runtime mapping. Found by the
 * cross-version parity test, which sets `date` because `date` is shared between R4 and R5.
 *
 * @see OperationCrossVersionParityTest for the test that found it
 */
final class OperationTemporalParameterMappingTest extends TestCase
{
    /**
     * @return \Generator<string, array{string}>
     */
    public static function versions(): \Generator
    {
        foreach (['R4', 'R4B', 'R5'] as $version) {
            yield $version => [$version];
        }
    }

    /**
     * A `dateTime` parameter survives the round trip with its precision intact.
     *
     * The precision cases are the point. A naive fix that parsed to a `DateTimeImmutable` and rendered
     * back with a fixed format would pass a bare equality check on the full timestamp and silently
     * widen `2026` into `2026-01-01T00:00:00+00:00` — changing what the resource asserts.
     */
    #[DataProvider('versions')]
    public function testDateTimePrecisionSurvivesTheRoundTrip(string $version): void
    {
        foreach (['2026', '2026-08', '2026-08-07', '2026-08-07T12:30:00Z'] as $literal) {
            $restored = $this->roundTrip($version, ['date' => $literal]);

            self::assertSame($literal, $restored->date, sprintf(
                'dateTime `%s` did not survive the round trip in %s — precision was widened or lost.',
                $literal,
                $version,
            ));
        }
    }

    /**
     * The emitted wire entry uses the `valueDateTime` slot and carries the literal, not an object.
     *
     * Round-trip identity alone cannot see a mapper that stores something exotic and reverses it
     * symmetrically; the wire form is what a server actually receives.
     */
    #[DataProvider('versions')]
    public function testTheWireEntryCarriesTheDateTimeLiteral(string $version): void
    {
        $parameters = OperationParameterMapper::createDefault(FhirVersion::from($version))
            ->toParameters($this->input($version, ['date' => '2026-08-07T12:30:00Z']));

        $entry = null;

        foreach ($parameters->parameter as $candidate) {
            if ($candidate->name === 'date') {
                $entry = $candidate;

                break;
            }
        }

        self::assertNotNull($entry, sprintf('No `date` parameter was emitted in %s.', $version));
        self::assertSame('2026-08-07T12:30:00Z', (string) $entry->value, sprintf(
            'The `date` value slot did not render the published literal in %s.',
            $version,
        ));
    }

    /**
     * A malformed temporal literal is refused, rather than reaching the wrapper as a TypeError.
     *
     * The distinction matters for callers: `OperationMappingException` names the parameter and its
     * type, while a `TypeError` from a generated constructor names neither and reads as a library bug.
     */
    #[DataProvider('versions')]
    public function testAMalformedTemporalLiteralRaisesAMappingException(string $version): void
    {
        $this->expectException(OperationMappingException::class);

        OperationParameterMapper::createDefault(FhirVersion::from($version))
            ->toParameters($this->input($version, ['date' => 'not-a-date']));
    }

    /**
     * Map a payload out and back, returning the restored payload.
     *
     * @param array<string, string> $arguments
     */
    private function roundTrip(string $version, array $arguments): object
    {
        $mapper     = OperationParameterMapper::createDefault(FhirVersion::from($version));
        $parameters = $mapper->toParameters($this->input($version, $arguments));

        return $mapper->fromParameters($parameters, $this->inputClass($version));
    }

    /**
     * @param array<string, string> $arguments
     */
    private function input(string $version, array $arguments): object
    {
        $class = $this->inputClass($version);

        return new $class(...['code' => 'AB', 'system' => 'http://acme.org/codes', ...$arguments]);
    }

    /**
     * @return class-string
     */
    private function inputClass(string $version): string
    {
        /** @var class-string $class */
        $class = sprintf(
            'Ardenexal\FHIRTools\Component\Models\%s\Operation\CodeSystemLookup\CodeSystemLookupInput',
            $version,
        );

        return $class;
    }
}

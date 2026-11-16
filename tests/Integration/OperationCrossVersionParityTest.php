<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Integration;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Serialization\Operation\OperationParameterMapper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * The same logical input, expressed through different versions' classes, produces the same wire form.
 *
 * Every version gets its own generated classes, because the same operation genuinely differs between
 * them — R5 `$lookup` declares `useSupplement` as an eighth IN parameter and `definition` as a sixth
 * OUT parameter, neither of which exists in R4. What must **not** differ is the wire representation of
 * the parameters the versions share: `code` is `{"name":"code","valueCode":"..."}` in R4, R4B and R5
 * alike, because `Parameters` is version-independent in shape.
 *
 * ## Why this is worth a test rather than an assumption
 *
 * This assertion mechanically catches the class of bug that made the plan's original design wrong: the
 * draft assumed R4's parameter model could be reused verbatim for R5, and the generator was very
 * nearly built on that basis. A per-version divergence in how a *shared* parameter serializes would be
 * invisible in single-version round-trip tests — each version round-trips itself perfectly while
 * disagreeing with its siblings — and would only surface as an interoperability failure against a real
 * server.
 *
 * The comparison is deliberately restricted to the **intersection**, computed from the published
 * definitions rather than listed here, so a version gaining a parameter does not fail this test. The
 * guard below is what stops that restriction from quietly emptying the comparison.
 *
 * @see GeneratedOperationsMatchPublishedDefinitionsTest for per-version fidelity to the definition
 */
final class OperationCrossVersionParityTest extends TestCase
{
    private const string LOOKUP_URL = 'http://hl7.org/fhir/OperationDefinition/CodeSystem-lookup';

    /**
     * Values chosen to be legal in every version, so any difference in output is the code's doing.
     *
     * Only monomorphic scalar parameters: `coding` and `property` are excluded because they are a
     * complex type and a backbone group, and constructing them identically across versions would be
     * asserting the datatype generator rather than operation parity.
     *
     * @var array<string, string>
     */
    private const array SHARED_INPUT = [
        'code'            => 'AB',
        'system'          => 'http://acme.org/codes',
        'version'         => '2026-01',
        'date'            => '2026-08-07T00:00:00Z',
        'displayLanguage' => 'en-GB',
    ];

    /**
     * @return \Generator<string, array{string, string}>
     */
    public static function versionPairs(): \Generator
    {
        yield 'R4 vs R5'  => ['R4', 'R5'];
        yield 'R4 vs R4B' => ['R4', 'R4B'];
        yield 'R4B vs R5' => ['R4B', 'R5'];
    }

    /**
     * Shared IN parameters serialize identically across every pair of versions.
     */
    #[DataProvider('versionPairs')]
    public function testSharedInputParametersSerializeIdentically(string $left, string $right): void
    {
        $shared = $this->sharedParameterNames($left, $right, 'in');

        $leftWire  = $this->emit($left);
        $rightWire = $this->emit($right);

        foreach (array_keys(self::SHARED_INPUT) as $name) {
            self::assertContains($name, $shared, sprintf(
                'Test fixture sets "%s", but it is not shared between %s and %s. Either the packages '
                . 'moved or the fixture is wrong; the comparison must only cover shared parameters.',
                $name,
                $left,
                $right,
            ));

            self::assertSame(
                $leftWire[$name]  ?? null,
                $rightWire[$name] ?? null,
                sprintf(
                    'Parameter "%s" serializes differently in %s and %s. `Parameters` is '
                    . 'version-independent in shape, so a shared parameter must produce the same wire '
                    . 'entry in both — a divergence here round-trips cleanly within each version while '
                    . 'failing against a real server.',
                    $name,
                    $left,
                    $right,
                ),
            );
        }
    }

    /**
     * The guard: the intersection is real, and genuinely narrower than either side.
     *
     * Without this, the test above passes vacuously if `sharedParameterNames()` ever returns
     * everything (a broken intersection) or nothing (a broken lookup). R5 is known to add
     * `useSupplement` to IN and `definition` to OUT, so a correct intersection is strictly smaller
     * than R5's parameter set and equal to R4's.
     */
    public function testTheIntersectionIsRealAndNarrowerThanR5(): void
    {
        $sharedIn  = $this->sharedParameterNames('R4', 'R5', 'in');
        $sharedOut = $this->sharedParameterNames('R4', 'R5', 'out');

        $r5In  = $this->parameterNames('R5', 'in');
        $r5Out = $this->parameterNames('R5', 'out');

        self::assertNotSame([], $sharedIn, 'No shared IN parameters found — the comparison is vacuous.');

        self::assertContains('useSupplement', $r5In, 'R5 $lookup no longer declares useSupplement; the packages moved.');
        self::assertNotContains('useSupplement', $sharedIn, 'useSupplement leaked into the shared set — the intersection is wrong.');

        self::assertContains('definition', $r5Out, 'R5 $lookup no longer declares definition; the packages moved.');
        self::assertNotContains('definition', $sharedOut, 'definition leaked into the shared set — the intersection is wrong.');
    }

    /**
     * R4 and R4B declare `$lookup` identically, and that is asserted rather than assumed.
     *
     * The plan folded R4B in with R4 on the grounds that its parameter model is byte-identical. That
     * is true today and is exactly the kind of claim that rots silently at the next package bump.
     */
    public function testR4AndR4bDeclareLookupIdentically(): void
    {
        foreach (['in', 'out'] as $use) {
            self::assertSame(
                $this->parameterNames('R4', $use),
                $this->parameterNames('R4B', $use),
                sprintf('R4 and R4B $lookup %s parameters have diverged.', strtoupper($use)),
            );
        }
    }

    /**
     * Build the shared input for a version and return its wire entries keyed by parameter name.
     *
     * @return array<string, array<string, mixed>>
     */
    private function emit(string $version): array
    {
        $fhirVersion = FhirVersion::from($version);
        $inputClass  = sprintf(
            'Ardenexal\FHIRTools\Component\Models\%s\Operation\CodeSystemLookup\CodeSystemLookupInput',
            $version,
        );

        $parameters = OperationParameterMapper::createDefault($fhirVersion)
            ->toParameters(new $inputClass(...self::SHARED_INPUT));

        $json = FHIRSerializationService::createDefault($fhirVersion)->serializeToJson($parameters);

        /** @var array{parameter?: list<array<string, mixed>>} $decoded */
        $decoded = json_decode($json, true, 512, \JSON_THROW_ON_ERROR);

        $byName = [];

        foreach ($decoded['parameter'] ?? [] as $entry) {
            if (is_array($entry) && is_string($entry['name'] ?? null)) {
                $byName[$entry['name']] = $entry;
            }
        }

        return $byName;
    }

    /**
     * Parameter names a version declares for `$lookup`, in published order.
     *
     * @return list<string>
     */
    private function parameterNames(string $version, string $use): array
    {
        $definition = GeneratedOperationCorpus::operations($version)[self::LOOKUP_URL] ?? null;

        self::assertIsArray($definition, sprintf('%s does not publish CodeSystem/$lookup.', $version));

        return array_values(array_filter(array_map(
            static fn (array $parameter): ?string => is_string($parameter['name'] ?? null) ? $parameter['name'] : null,
            GeneratedOperationCorpus::parameters($definition, $use),
        )));
    }

    /**
     * @return list<string>
     */
    private function sharedParameterNames(string $left, string $right, string $use): array
    {
        return array_values(array_intersect(
            $this->parameterNames($left, $use),
            $this->parameterNames($right, $use),
        ));
    }
}

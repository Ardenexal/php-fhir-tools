<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\CodeGeneration\tests\Unit\Parser;

use Ardenexal\FHIRTools\Component\CodeGeneration\Parser\AllowedTypeReader;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Verifies AllowedTypeReader resolves the same variant set across FHIR versions.
 *
 * The premise of operation code generation is that one mapper serves every version, with only the
 * metadata it reads differing. Allowed types are where that premise is most likely to break: R5
 * added a first-class `parameter.allowedType` element, but no shipped OperationDefinition in any
 * core package populates it — the legacy `operationdefinition-allowed-type` extension remains the
 * only live source, including on R5.
 *
 * A reader that follows the spec prose and reads `allowedType` on R5 therefore produces ZERO
 * variants for R5 CodeSystem/$lookup, silently generating an untyped parameter. These tests exist
 * to make that failure loud.
 *
 * Fixtures are the published definitions copied verbatim from the R4, R4B and R5 core packages.
 */
final class AllowedTypeReaderTest extends TestCase
{
    /**
     * The seven concrete types CodeSystem/$lookup permits on `property.value`, sorted.
     *
     * R4 lists these in specification order and R5 alphabetically; the reader sorts, so a single
     * expectation serves both. That is the point — an unsorted reader would make this test
     * version-specific and hide the parity the milestone is proving.
     *
     * @var list<string>
     */
    private const array LOOKUP_VALUE_TYPES = [
        'Coding',
        'boolean',
        'code',
        'dateTime',
        'decimal',
        'integer',
        'string',
    ];

    /**
     * R4, R4B and R5 resolve `property.value` to an identical seven-type set.
     *
     * This is the milestone's load-bearing assertion. R4/R4B have no `allowedType` element at all
     * and R5 ships it empty, so all three sets are built from the extension — proving the union
     * reader is version-agnostic in practice, not just in principle.
     */
    #[DataProvider('versionProvider')]
    public function testAllVersionsResolveTheSameVariantSetForPropertyValue(string $version): void
    {
        $parameter = self::parameterAtPath($version, [['property', 'out'], ['value', 'out']]);

        self::assertSame(
            self::LOOKUP_VALUE_TYPES,
            (new AllowedTypeReader())->read($parameter),
            sprintf(
                '%s property.value did not resolve to the expected seven types. An empty result means '
                . 'the reader consulted only parameter.allowedType, which no core package populates.',
                strtoupper($version),
            ),
        );
    }

    /**
     * The nested `property.subproperty.value` parameter carries the same set.
     *
     * Confirms the reader operates on any parameter node, including one reached through `part[]`
     * recursion, rather than only on top-level parameters.
     */
    #[DataProvider('versionProvider')]
    public function testNestedSubpropertyValueResolvesTheSameVariantSet(string $version): void
    {
        $parameter = self::parameterAtPath($version, [['property', 'out'], ['subproperty', 'out'], ['value', 'out']]);

        self::assertSame(
            self::LOOKUP_VALUE_TYPES,
            (new AllowedTypeReader())->read($parameter),
            sprintf('%s property.subproperty.value did not resolve to the expected seven types.', strtoupper($version)),
        );
    }

    /**
     * Documents the fact the reader exists to work around: `allowedType` is empty everywhere.
     *
     * If this test ever fails because a fixture populates `allowedType`, that is good news — but
     * the reader's union behaviour must then be re-verified rather than assumed, because the two
     * sources would be live simultaneously for the first time.
     */
    #[DataProvider('versionProvider')]
    public function testNoShippedDefinitionPopulatesTheAllowedTypeElement(string $version): void
    {
        $parameter = self::parameterAtPath($version, [['property', 'out'], ['value', 'out']]);

        self::assertSame(
            [],
            $parameter['allowedType'] ?? [],
            sprintf(
                '%s now populates parameter.allowedType. The reader still unions both sources, but the '
                . 'assumption that the extension is the sole live source no longer holds.',
                strtoupper($version),
            ),
        );
        self::assertCount(
            7,
            array_filter(
                $parameter['extension'] ?? [],
                static fn (array $e): bool => ($e['url'] ?? null)
                    === 'http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type',
            ),
            sprintf('%s no longer carries seven allowed-type extensions.', strtoupper($version)),
        );
    }

    /**
     * The R5 element is read when present, so definitions adopting it are not silently ignored.
     */
    public function testAllowedTypeElementIsReadWhenPopulated(): void
    {
        $types = (new AllowedTypeReader())->read([
            'name'        => 'value',
            'use'         => 'out',
            'type'        => 'Element',
            'allowedType' => ['string', 'Quantity'],
        ]);

        self::assertSame(['Quantity', 'string'], $types);
    }

    /**
     * Both sources contribute, and a type declared in both appears once.
     */
    public function testBothSourcesAreUnionedAndDeduplicated(): void
    {
        $types = (new AllowedTypeReader())->read([
            'name'        => 'value',
            'allowedType' => ['string', 'Quantity'],
            'extension'   => [
                ['url' => 'http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type', 'valueUri' => 'string'],
                ['url' => 'http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type', 'valueUri' => 'code'],
                ['url' => 'http://example.org/unrelated-extension', 'valueUri' => 'ShouldNotAppear'],
            ],
        ]);

        self::assertSame(['Quantity', 'code', 'string'], $types);
    }

    /**
     * A monomorphic parameter yields an empty set rather than a spurious variant.
     */
    public function testMonomorphicParameterYieldsNoVariants(): void
    {
        $parameter = self::parameterAtPath('r4', [['code', 'in']]);

        self::assertSame('code', $parameter['type'] ?? null, 'Fixture drift: $lookup `code` is no longer type `code`.');
        self::assertSame([], (new AllowedTypeReader())->read($parameter));
    }

    /**
     * Type codes map onto the existing `value[x]` element-name convention.
     *
     * Complex types keep their leading capital (`Coding` => `valueCoding`) and camelCase primitives
     * are pascal-cased (`dateTime` => `valueDateTime`).
     */
    #[DataProvider('jsonKeyProvider')]
    public function testJsonKeyFollowsTheValueXConvention(string $fhirType, string $expected): void
    {
        self::assertSame($expected, AllowedTypeReader::jsonKeyFor($fhirType));
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function jsonKeyProvider(): iterable
    {
        yield 'primitive'           => ['code', 'valueCode'];
        yield 'camelCase primitive' => ['dateTime', 'valueDateTime'];
        yield 'complex type'        => ['Coding', 'valueCoding'];
        yield 'scalar'              => ['boolean', 'valueBoolean'];
        // Digit mid-token is where naive pascal-casing breaks. M02 runs this over ~150 operations,
        // so the two core type codes that exercise it are pinned here rather than discovered there.
        yield 'digit in type code'  => ['base64Binary', 'valueBase64Binary'];
        yield 'abbreviated int'     => ['positiveInt', 'valuePositiveInt'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function versionProvider(): iterable
    {
        yield 'R4'  => ['r4'];
        yield 'R4B' => ['r4b'];
        yield 'R5'  => ['r5'];
    }

    /**
     * Walk a `parameter[]`/`part[]` path in a fixture and return the addressed parameter node.
     *
     * Name alone does not address a parameter. $lookup declares `property` twice at the top level —
     * once `use: in` typed `code`, once `use: out` as a backbone group — which is precisely the
     * collision path-keyed class naming exists to resolve. Each segment therefore carries the `use`
     * that disambiguates it, and resolution asserts a unique match rather than taking the first.
     *
     * @param list<array{string, string}> $path [name, use] pairs, outermost first
     *
     * @return array<string, mixed>
     */
    private static function parameterAtPath(string $version, array $path): array
    {
        $file = sprintf('%s/../../Fixtures/OperationDefinitions/%s-CodeSystem-lookup.json', __DIR__, $version);

        $contents = file_get_contents($file);
        self::assertIsString($contents, sprintf('Fixture %s is unreadable.', $file));

        $definition = json_decode($contents, true, 512, \JSON_THROW_ON_ERROR);
        self::assertIsArray($definition);

        $candidates = $definition['parameter'] ?? [];
        $node       = null;

        foreach ($path as [$name, $use]) {
            self::assertIsArray($candidates);

            $matches = array_values(array_filter(
                $candidates,
                static fn (mixed $c): bool => is_array($c)
                    && ($c['name'] ?? null) === $name
                    && ($c['use'] ?? null)  === $use,
            ));

            self::assertCount(
                1,
                $matches,
                sprintf(
                    'Fixture drift: %s $lookup does not resolve "%s" (use: %s) to exactly one parameter at this level.',
                    strtoupper($version),
                    $name,
                    $use,
                ),
            );

            $node       = $matches[0];
            $candidates = $node['part'] ?? [];
        }

        self::assertIsArray($node);

        /** @var array<string, mixed> $node */
        return $node;
    }
}

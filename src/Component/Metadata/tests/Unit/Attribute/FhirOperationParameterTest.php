<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Attribute;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Attribute\Fixture\AwkwardNamesInputFixture;
use Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Attribute\Fixture\LookupOutputFixture;
use Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Attribute\Fixture\LookupOutputPropertyFixture;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Pins the #[FhirOperationParameter] contract before ~150 generated classes depend on it.
 *
 * The attribute's signature is fixed deliberately early: changing a field name or type after the
 * generator has emitted classes for every operation in three FHIR versions means regenerating and
 * re-reviewing all of them. These tests assert the properties that make it worth fixing — the
 * cardinality semantics, the wire/PHP name split, and reflection reachability for the mapper.
 */
final class FhirOperationParameterTest extends TestCase
{
    /**
     * `max` is a string and '*' means unbounded, so int-casting inverts the unbounded case.
     */
    #[DataProvider('cardinalityProvider')]
    public function testIsCollectionTreatsStarAsUnbounded(string $max, bool $expectedCollection): void
    {
        $parameter = new FhirOperationParameter(name: 'p', phpName: 'p', use: 'in', min: 0, max: $max);

        self::assertSame($expectedCollection, $parameter->isCollection());
    }

    /**
     * @return iterable<string, array{string, bool}>
     */
    public static function cardinalityProvider(): iterable
    {
        yield 'unbounded'        => ['*', true];
        yield 'single'           => ['1', false];
        yield 'prohibited'       => ['0', false];
        yield 'bounded multiple' => ['2', true];
        yield 'large bound'      => ['10', true];
    }

    /**
     * A naive `(int) $max > 1` implementation classifies '*' as 0 — scalar — which is the exact
     * inversion of the truth. Asserted separately because it is the failure mode, not an edge case.
     */
    public function testUnboundedIsNotMisreadAsZero(): void
    {
        $unbounded = new FhirOperationParameter(name: 'p', phpName: 'p', use: 'in', min: 0, max: '*');

        self::assertSame(0, (int) $unbounded->max, 'Guard premise changed: (int) "*" is no longer 0.');
        self::assertTrue($unbounded->isCollection(), 'Unbounded parameter was classified as scalar.');
    }

    #[DataProvider('requirednessProvider')]
    public function testIsRequiredFollowsMin(int $min, bool $expectedRequired): void
    {
        $parameter = new FhirOperationParameter(name: 'p', phpName: 'p', use: 'in', min: $min, max: '1');

        self::assertSame($expectedRequired, $parameter->isRequired());
    }

    /**
     * @return iterable<string, array{int, bool}>
     */
    public static function requirednessProvider(): iterable
    {
        yield 'optional' => [0, false];
        yield 'required' => [1, true];
        yield 'required multiple' => [2, true];
    }

    /**
     * Wire names survive verbatim — including the ones no identifier-slugging round-trips.
     *
     * Each case is a real wire name from the core packages, though they are drawn from several
     * different operations rather than one (see AwkwardNamesInputFixture). `targetIdentifer.preferred`
     * is R5's published typo (missing the 'i' in 'Identifier'); emitting the corrected spelling
     * would produce a parameter no server recognises.
     */
    #[DataProvider('wireNameProvider')]
    public function testWireNameIsStoredVerbatimAndSeparatelyFromThePhpName(
        string $phpName,
        string $expectedWireName,
    ): void {
        $parameters = self::attributesOf(AwkwardNamesInputFixture::class);

        self::assertArrayHasKey($phpName, $parameters, sprintf('Fixture has no parameter with phpName "%s".', $phpName));
        self::assertSame($expectedWireName, $parameters[$phpName]->name);
    }

    /**
     * @return iterable<string, array{string, string}>
     */
    public static function wireNameProvider(): iterable
    {
        yield 'reserved word'      => ['useParameter', 'use'];
        yield 'leading underscore' => ['count', '_count'];
        yield 'hyphenated'         => ['checkSystemVersion', 'check-system-version'];
        yield 'dotted with typo'   => ['targetIdentiferPreferred', 'targetIdentifer.preferred'];
        yield 'already legal'      => ['code', 'code'];
    }

    /**
     * The wire name is not recoverable from the PHP name, which is why both are stored.
     *
     * `useParameter` -> `use` and `count` -> `_count` are not derivable by any general rule, and
     * two distinct wire names could legally slug to the same identifier.
     */
    public function testPhpNameAndWireNameDivergeAndNeitherDerivesTheOther(): void
    {
        $parameters = self::attributesOf(AwkwardNamesInputFixture::class);

        $diverging = array_filter(
            $parameters,
            static fn (FhirOperationParameter $p): bool => $p->name !== $p->phpName,
        );

        self::assertGreaterThanOrEqual(
            4,
            count($diverging),
            'Fixture no longer exercises names that need a PHP identifier different from the wire name.',
        );

        foreach ($diverging as $parameter) {
            self::assertNotSame('', $parameter->phpName);
            self::assertNotSame('', $parameter->name);
        }
    }

    /**
     * The attribute is reachable through ReflectionProperty on a promoted constructor property.
     *
     * This matters because a documented trap in this repo is that Symfony's AttributeLoader reads
     * ReflectionProperty::getAttributes() and not ReflectionParameter::getAttributes(); an attribute
     * declared TARGET_PARAMETER only would be silently invisible there. The mapper is
     * reflection-driven, so both routes must work.
     */
    public function testAttributeIsReachableViaBothPropertyAndParameterReflection(): void
    {
        $viaProperty = (new \ReflectionProperty(AwkwardNamesInputFixture::class, 'code'))
            ->getAttributes(FhirOperationParameter::class);

        self::assertCount(1, $viaProperty, 'Attribute is invisible to ReflectionProperty on a promoted property.');
        self::assertSame('code', $viaProperty[0]->newInstance()->name);

        $constructor = (new \ReflectionClass(AwkwardNamesInputFixture::class))->getConstructor();
        self::assertNotNull($constructor);

        $viaParameter = $constructor->getParameters()[0]->getAttributes(FhirOperationParameter::class);

        self::assertCount(1, $viaParameter, 'Attribute is invisible to ReflectionParameter.');
        self::assertSame('code', $viaParameter[0]->newInstance()->name);
    }

    /**
     * A pure `part` group carries no `type` and points at its nested class instead.
     */
    public function testPartGroupHasNullTypeAndAPartClass(): void
    {
        $property = self::attributesOf(LookupOutputFixture::class)['property'];

        self::assertNull($property->type, 'A part group must not claim a FHIR type of its own.');
        self::assertSame(LookupOutputPropertyFixture::class, $property->partClass);
        self::assertTrue($property->isCollection());
    }

    /**
     * The same wire name resolves to different parameters depending on `use`.
     *
     * $lookup declares `property` twice at the top level: `use: in` typed `code`, and `use: out` as
     * a backbone group. Name alone does not address a parameter, which is what forces path-keyed
     * nested classes.
     */
    public function testTheSameWireNameCarriesDifferentSemanticsPerUse(): void
    {
        $input  = self::attributesOf(AwkwardNamesInputFixture::class)['property'];
        $output = self::attributesOf(LookupOutputFixture::class)['property'];

        self::assertSame('property', $input->name);
        self::assertSame('property', $output->name);

        self::assertSame('in', $input->use);
        self::assertSame('out', $output->use);

        self::assertSame('code', $input->type);
        self::assertNull($output->type);

        self::assertNull($input->partClass);
        self::assertNotNull($output->partClass);
    }

    /**
     * Variants use the existing choice shape, so the serializer's machinery can consume them unchanged.
     */
    public function testVariantsMatchTheEstablishedChoiceShape(): void
    {
        $value = self::attributesOf(LookupOutputPropertyFixture::class)['value'];

        self::assertNotNull($value->variants);
        self::assertCount(7, $value->variants);

        foreach ($value->variants as $variant) {
            self::assertSame(
                ['fhirType', 'propertyKind', 'phpType', 'jsonKey'],
                array_keys($variant),
                'Variant keys drifted from FhirProperty::$variants / PropertyVariantMetadata.',
            );
        }

        self::assertSame(
            ['valueCoding', 'valueBoolean', 'valueCode', 'valueDateTime', 'valueDecimal', 'valueInteger', 'valueString'],
            array_column($value->variants, 'jsonKey'),
        );
    }

    /**
     * R5-only fields default to an empty/absent state rather than requiring R4 callers to pass them.
     */
    public function testR5OnlyFieldsDefaultToAbsent(): void
    {
        $r4Style = new FhirOperationParameter(name: 'p', phpName: 'p', use: 'in', min: 0, max: '1', type: 'code');

        self::assertSame([], $r4Style->scope);
        self::assertNull($r4Style->searchType);
        self::assertNull($r4Style->variants);
        self::assertNull($r4Style->partClass);
        self::assertNull($r4Style->documentation);
    }

    /**
     * `scope` is 0..* in R5, so it is a list — not a single code.
     */
    public function testScopeIsAList(): void
    {
        $parameter = self::attributesOf(AwkwardNamesInputFixture::class)['targetIdentiferPreferred'];

        self::assertSame(['instance', 'type'], $parameter->scope);
    }

    /**
     * Fixtures honour the two published invariants that constrain `searchType`.
     *
     * opd-2: `searchType.exists() implies type = 'string'`.
     * opd-4: `(use = 'out') implies searchType.empty()`.
     *
     * The attribute does not enforce these — it is metadata, and enforcement belongs to generation.
     * This asserts the fixtures are legal FHIR, so M02 cannot copy an invariant-violating example.
     */
    public function testFixturesHonourTheSearchTypeInvariants(): void
    {
        $all = [
            ...array_values(self::attributesOf(AwkwardNamesInputFixture::class)),
            ...array_values(self::attributesOf(LookupOutputFixture::class)),
            ...array_values(self::attributesOf(LookupOutputPropertyFixture::class)),
        ];

        $withSearchType = array_filter($all, static fn (FhirOperationParameter $p): bool => $p->searchType !== null);

        self::assertNotEmpty($withSearchType, 'No fixture exercises searchType.');

        foreach ($withSearchType as $parameter) {
            self::assertSame('string', $parameter->type, sprintf('opd-2 violated by "%s".', $parameter->name));
            self::assertSame('in', $parameter->use, sprintf('opd-4 violated by "%s".', $parameter->name));
        }
    }

    /**
     * Complex variants carry a resolvable FQCN in `phpType`, not a bare type name.
     *
     * PropertyVariantMetadata documents phpType as "FQCN for class types", and the serializer
     * instantiates it directly. A bare 'Coding' passes a keys-only check and fails at runtime.
     */
    public function testComplexVariantsCarryAResolvableFqcn(): void
    {
        $value = self::attributesOf(LookupOutputPropertyFixture::class)['value'];
        self::assertNotNull($value->variants);

        $complex = array_filter($value->variants, static fn (array $v): bool => $v['propertyKind'] === 'complex');
        self::assertNotEmpty($complex);

        foreach ($complex as $variant) {
            self::assertTrue(
                class_exists($variant['phpType']),
                sprintf('Variant phpType "%s" does not resolve to a class.', $variant['phpType']),
            );
        }
    }

    /**
     * Read every #[FhirOperationParameter] off a class's promoted properties, keyed by PHP name.
     *
     * @param class-string $class
     *
     * @return array<string, FhirOperationParameter>
     */
    private static function attributesOf(string $class): array
    {
        $found = [];

        foreach ((new \ReflectionClass($class))->getProperties() as $property) {
            foreach ($property->getAttributes(FhirOperationParameter::class) as $attribute) {
                $found[$property->getName()] = $attribute->newInstance();
            }
        }

        return $found;
    }
}

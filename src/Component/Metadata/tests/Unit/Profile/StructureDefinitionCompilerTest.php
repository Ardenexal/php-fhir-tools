<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Tests\Unit\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileObligation;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Metadata\Profile\CompiledAttribute;
use Ardenexal\FHIRTools\Component\Metadata\Profile\StructureDefinitionCompiler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Count;

/**
 * Verifies the contract runtime validation relies on: compiled attributes instantiate into the
 * same attribute objects the generator renders, split into constraints and metadata, come out in
 * a fixed order, and untranslatable rules are reported.
 *
 * What each rule compiles to is covered through the generator by the FHIRProfile*EmissionTest
 * suites in CodeGeneration, which render this compiler's output.
 *
 * @see StructureDefinitionCompiler
 */
final class StructureDefinitionCompilerTest extends TestCase
{
    private const string URL = 'http://example.org/fhir/StructureDefinition/test-observation';

    public function testCompiledAttributesInstantiateWithTheProfileGroup(): void
    {
        $compiled = (new StructureDefinitionCompiler())->compile(self::observationProfile());

        self::assertSame(self::URL, $compiled->profileUrl);
        self::assertNotSame([], $compiled->attributes);

        foreach ($compiled->attributes as $attribute) {
            $instance = $attribute->instantiate();

            self::assertInstanceOf($attribute->class, $instance);
            self::assertTrue(property_exists($instance, 'groups'), $attribute->class . ' has no groups');
            self::assertSame([self::URL], $instance->groups);
        }
    }

    public function testAttributesSplitIntoConstraintsAndMetadata(): void
    {
        $compiled = (new StructureDefinitionCompiler())->compile(self::observationProfile());

        self::assertSame(
            [FHIRProfileConstraint::class, FHIRProfileConstraint::class, FHIRSliceConstraint::class],
            self::classes($compiled->constraints()),
        );
        self::assertSame(
            [FHIRProfileMustSupport::class, FHIRSlicingRules::class, FHIRProfileObligation::class],
            self::classes($compiled->metadata()),
        );
    }

    public function testAttributesComeOutInGeneratorOrder(): void
    {
        $compiled = (new StructureDefinitionCompiler())->compile(self::observationProfile());

        // Constraints, then must-support, then slicing, then obligations: generated classes depend on it
        self::assertSame(
            [
                FHIRProfileConstraint::class,
                FHIRProfileConstraint::class,
                FHIRProfileMustSupport::class,
                FHIRSlicingRules::class,
                FHIRSliceConstraint::class,
                FHIRProfileObligation::class,
            ],
            self::classes($compiled->attributes),
        );

        $status = $compiled->attributes[0]->arguments;
        self::assertSame(['path' => 'status', 'constraint' => Count::class, 'options' => ['min' => 1], 'groups' => [self::URL]], $status);
        self::assertSame(FHIRFixedValue::class, $compiled->attributes[1]->arguments['constraint']);
    }

    public function testSliceOnUndefinedSlicingIsReportedNotDropped(): void
    {
        $sd = [
            'url'          => self::URL,
            'differential' => ['element' => [
                ['id' => 'Observation', 'path' => 'Observation'],
                ['id' => 'Observation.component:systolic', 'path' => 'Observation.component', 'sliceName' => 'systolic', 'min' => 1],
            ]],
        ];

        $compiled = (new StructureDefinitionCompiler())->compile($sd);

        self::assertSame([], $compiled->attributes);
        self::assertCount(1, $compiled->diagnostics);
        self::assertSame('Observation.component', $compiled->diagnostics[0]->elementId);
        self::assertSame(['profileUrl' => self::URL], $compiled->diagnostics[0]->context);
    }

    /**
     * An `exists` slice is told apart by its own cardinality for the discriminator path: `min` 1
     * means the element is present, `max` "0" that it is absent.
     */
    public function testExistsSliceTakesItsPresenceFromItsCardinalityAtThePath(): void
    {
        $sd = self::entrySlicing('openAtEnd', ['type' => 'exists', 'path' => 'resource'], [
            ['id' => 'Bundle.entry:withResource', 'path' => 'Bundle.entry', 'sliceName' => 'withResource', 'min' => 1, 'max' => '1'],
            ['id' => 'Bundle.entry:withResource.resource', 'path' => 'Bundle.entry.resource', 'min' => 1],
            ['id' => 'Bundle.entry:withoutResource', 'path' => 'Bundle.entry', 'sliceName' => 'withoutResource'],
            ['id' => 'Bundle.entry:withoutResource.resource', 'path' => 'Bundle.entry.resource', 'max' => '0'],
        ]);

        $compiled = (new StructureDefinitionCompiler())->compile($sd);
        $slices   = self::sliceArguments($compiled->attributes);

        self::assertTrue($slices['withResource']['discriminatorValue']);
        self::assertSame(1, $slices['withResource']['min']);
        self::assertFalse($slices['withoutResource']['discriminatorValue']);
        self::assertSame(['openAtEnd'], self::slicingRules($compiled->attributes));
        self::assertSame([], $compiled->diagnostics);
    }

    /**
     * A slice that states nothing about the `exists` path can never be matched, so it has no
     * value, no minimum, and the openAtEnd rules relax to open with a diagnostic. The core
     * subscription notification bundle slices `entry` this way, on a path no slice constrains.
     */
    public function testExistsSliceWithNoPresenceRelaxesItsSlicingToOpen(): void
    {
        $sd = self::entrySlicing('openAtEnd', ['type' => 'exists', 'path' => 'entry'], [
            ['id' => 'Bundle.entry:first', 'path' => 'Bundle.entry', 'sliceName' => 'first', 'min' => 1, 'max' => '1'],
            ['id' => 'Bundle.entry:first.resource', 'path' => 'Bundle.entry.resource', 'min' => 1],
        ]);

        $compiled = (new StructureDefinitionCompiler())->compile($sd);
        $slices   = self::sliceArguments($compiled->attributes);

        self::assertArrayNotHasKey('discriminatorValue', $slices['first']);
        self::assertSame(0, $slices['first']['min']);
        self::assertSame(['open'], self::slicingRules($compiled->attributes));
        self::assertCount(1, $compiled->diagnostics);
        self::assertSame('Bundle.entry', $compiled->diagnostics[0]->elementId);
        self::assertStringContainsString('first', $compiled->diagnostics[0]->message);
    }

    /**
     * Closed slicing on a discriminator the validator cannot evaluate (here `resolve()`, as in the
     * core lipid profile) is enforced as open, and its default slice is left out so items belonging
     * to the named slices are not held to the default slice's rules. Matchable slicing keeps its rules.
     */
    public function testClosedSlicingWithAnUnmatchableSliceIsEnforcedAsOpen(): void
    {
        $sd = self::entrySlicing('closed', ['type' => 'value', 'path' => 'resolve().code'], [
            ['id' => 'Bundle.entry:cholesterol', 'path' => 'Bundle.entry', 'sliceName' => 'cholesterol', 'min' => 1, 'max' => '1'],
            ['id' => 'Bundle.entry:@default', 'path' => 'Bundle.entry', 'sliceName' => '@default'],
            ['id' => 'Bundle.entry:@default.search', 'path' => 'Bundle.entry.search', 'max' => '0'],
        ]);

        $compiled = (new StructureDefinitionCompiler())->compile($sd);

        self::assertSame(['cholesterol'], array_keys(self::sliceArguments($compiled->attributes)));
        self::assertSame(['open'], self::slicingRules($compiled->attributes));
        self::assertCount(1, $compiled->diagnostics);

        $matchable = self::entrySlicing('closed', ['type' => 'value', 'path' => 'fullUrl'], [
            ['id' => 'Bundle.entry:status', 'path' => 'Bundle.entry', 'sliceName' => 'status', 'min' => 1],
            ['id' => 'Bundle.entry:status.fullUrl', 'path' => 'Bundle.entry.fullUrl', 'fixedUri' => 'urn:status'],
        ]);

        self::assertSame(['closed'], self::slicingRules((new StructureDefinitionCompiler())->compile($matchable)->attributes));
    }

    /**
     * Slicing beneath a slice travels in that slice's rules, and relaxes the same way.
     */
    public function testUnmatchableSlicingBeneathASliceIsEnforcedAsOpen(): void
    {
        $sd = self::entrySlicing('open', ['type' => 'value', 'path' => 'fullUrl'], [
            ['id' => 'Bundle.entry:status', 'path' => 'Bundle.entry', 'sliceName' => 'status'],
            ['id' => 'Bundle.entry:status.fullUrl', 'path' => 'Bundle.entry.fullUrl', 'fixedUri' => 'urn:status'],
            [
                'id'      => 'Bundle.entry:status.link',
                'path'    => 'Bundle.entry.link',
                'slicing' => ['discriminator' => [['type' => 'type', 'path' => '$this']], 'rules' => 'closed'],
            ],
            ['id' => 'Bundle.entry:status.link:self', 'path' => 'Bundle.entry.link', 'sliceName' => 'self'],
        ]);

        $compiled = (new StructureDefinitionCompiler())->compile($sd);
        $rules    = self::sliceArguments($compiled->attributes)['status']['rules'];

        $slicing = array_values(array_filter($rules, static fn (array $rule): bool => $rule['constraint'] === FHIRSlicingRules::class));
        self::assertCount(1, $slicing);
        self::assertSame('open', $slicing[0]['options']['rules']);
        self::assertCount(1, $compiled->diagnostics);
        self::assertSame('Bundle.entry:status.link', $compiled->diagnostics[0]->elementId);
    }

    /**
     * A Bundle profile slicing `entry` with the given rules and discriminator, plus $slices.
     *
     * @param array{type: string, path: string} $discriminator
     * @param list<array<string, mixed>>        $slices
     *
     * @return array<string, mixed>
     */
    private static function entrySlicing(string $rules, array $discriminator, array $slices): array
    {
        $elements = [
            ['id' => 'Bundle', 'path' => 'Bundle'],
            ['id' => 'Bundle.entry', 'path' => 'Bundle.entry', 'slicing' => ['discriminator' => [$discriminator], 'rules' => $rules]],
            ...$slices,
        ];

        return ['url' => self::URL, 'differential' => ['element' => $elements], 'snapshot' => ['element' => $elements]];
    }

    /**
     * Each compiled FHIRSliceConstraint's arguments, by slice name.
     *
     * @param list<CompiledAttribute> $attributes
     *
     * @return array<string, array<string, mixed>>
     */
    private static function sliceArguments(array $attributes): array
    {
        $slices = [];
        foreach ($attributes as $attribute) {
            if ($attribute->class === FHIRSliceConstraint::class) {
                $slices[(string) $attribute->arguments['sliceName']] = $attribute->arguments;
            }
        }

        return $slices;
    }

    /**
     * The rules of each compiled FHIRSlicingRules, in order.
     *
     * @param list<CompiledAttribute> $attributes
     *
     * @return list<string>
     */
    private static function slicingRules(array $attributes): array
    {
        $rules = [];
        foreach ($attributes as $attribute) {
            if ($attribute->class === FHIRSlicingRules::class) {
                $rules[] = (string) $attribute->arguments['rules'];
            }
        }

        return $rules;
    }

    /**
     * @param list<CompiledAttribute> $attributes
     *
     * @return list<string>
     */
    private static function classes(array $attributes): array
    {
        return array_map(static fn (CompiledAttribute $a): string => $a->class, $attributes);
    }

    /**
     * A profile with one rule of each kind: cardinality, fixed value, must-support, slicing,
     * a slice, and a snapshot obligation.
     *
     * @return array<string, mixed>
     */
    private static function observationProfile(): array
    {
        return [
            'url'          => self::URL,
            'differential' => ['element' => [
                ['id' => 'Observation', 'path' => 'Observation'],
                ['id' => 'Observation.status', 'path' => 'Observation.status', 'min' => 1, 'fixedCode' => 'final', 'mustSupport' => true],
                [
                    'id'      => 'Observation.category',
                    'path'    => 'Observation.category',
                    'slicing' => ['discriminator' => [['type' => 'pattern', 'path' => '$this']], 'rules' => 'open'],
                ],
                ['id' => 'Observation.category:vs', 'path' => 'Observation.category', 'sliceName' => 'vs', 'min' => 1, 'max' => '1'],
            ]],
            'snapshot' => ['element' => [
                ['id' => 'Observation', 'path' => 'Observation'],
                [
                    'id'        => 'Observation.status',
                    'path'      => 'Observation.status',
                    'extension' => [[
                        'url'       => 'http://hl7.org/fhir/StructureDefinition/obligation',
                        'extension' => [['url' => 'code', 'valueCode' => 'SHALL:populate']],
                    ]],
                ],
            ]],
        ];
    }
}

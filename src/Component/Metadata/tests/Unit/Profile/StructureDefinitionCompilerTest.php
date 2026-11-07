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

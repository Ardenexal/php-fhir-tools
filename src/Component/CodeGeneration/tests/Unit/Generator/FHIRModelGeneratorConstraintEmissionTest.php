<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRModelGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\Printer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use Nette\PhpGenerator\Attribute;

class FHIRModelGeneratorConstraintEmissionTest extends TestCase
{
    private const string TEST_NS = 'Ardenexal\\FHIRTools\\ConstraintEmissionTest';

    private FHIRModelGenerator $generator;

    private BuilderContext $context;

    protected function setUp(): void
    {
        $this->generator = new FHIRModelGenerator();
        $this->context   = new BuilderContext();
        $this->context->addElementNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Resource'));
        $this->context->addDatatypeNamespace('R4', new PhpNamespace(self::TEST_NS . '\\DataType'));
        $this->context->addPrimitiveNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Primitive'));
        $this->context->addEnumNamespace('R4', new PhpNamespace(self::TEST_NS . '\\Enum'));
    }

    // -------------------------------------------------------------------------
    // Count constraints on arrays
    // -------------------------------------------------------------------------

    public function testCountMinEmittedForRequiredArrayProperty(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('CountMinType', 'complex-type', [
                ['path' => 'CountMinType.item', 'min' => 1, 'max' => '*', 'type' => [['code' => 'http://hl7.org/fhirpath/System.String']], 'base' => ['path' => 'CountMinType.item']],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('item')->getAttributes(Count::class);
        self::assertNotEmpty($attrs, '#[Count] must be emitted for array with min:1');
        self::assertSame(1, $attrs[0]->newInstance()->min, 'Count::$min must be 1');
    }

    public function testCountMaxEmittedForBoundedArrayProperty(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('CountMaxType', 'complex-type', [
                ['path' => 'CountMaxType.item', 'min' => 0, 'max' => '3', 'type' => [['code' => 'http://hl7.org/fhirpath/System.String']], 'base' => ['path' => 'CountMaxType.item']],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('item')->getAttributes(Count::class);
        self::assertNotEmpty($attrs, '#[Count] must be emitted for array with numeric max');
        self::assertSame(3, $attrs[0]->newInstance()->max, 'Count::$max must be 3');
    }

    public function testCountNotEmittedForUnboundedOptionalArray(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('CountUnboundedType', 'complex-type', [
                ['path' => 'CountUnboundedType.item', 'min' => 0, 'max' => '*', 'type' => [['code' => 'http://hl7.org/fhirpath/System.String']], 'base' => ['path' => 'CountUnboundedType.item']],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('item')->getAttributes(Count::class);
        self::assertEmpty($attrs, '#[Count] must NOT be emitted when min:0 and max:*');
    }

    // -------------------------------------------------------------------------
    // Length constraint
    // -------------------------------------------------------------------------

    public function testLengthMaxEmitted(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('LengthType', 'complex-type', [
                ['path' => 'LengthType.value', 'min' => 0, 'max' => '1', 'maxLength' => 64, 'type' => [['code' => 'http://hl7.org/fhirpath/System.String']], 'base' => ['path' => 'LengthType.value']],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('value')->getAttributes(Length::class);
        self::assertNotEmpty($attrs, '#[Length] must be emitted when maxLength is present');
        self::assertSame(64, $attrs[0]->newInstance()->max, 'Length::$max must be 64');
    }

    public function testLengthNotEmittedWhenAbsent(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('NoLengthType', 'complex-type', [
                ['path' => 'NoLengthType.value', 'min' => 0, 'max' => '1', 'type' => [['code' => 'http://hl7.org/fhirpath/System.String']], 'base' => ['path' => 'NoLengthType.value']],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('value')->getAttributes(Length::class);
        self::assertEmpty($attrs, '#[Length] must NOT be emitted when maxLength is absent');
    }

    // -------------------------------------------------------------------------
    // Range constraint
    // -------------------------------------------------------------------------

    public function testRangeMinMaxEmitted(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('RangeType', 'complex-type', [
                ['path' => 'RangeType.score', 'min' => 0, 'max' => '1', 'minValueDecimal' => '0', 'maxValueDecimal' => '100', 'type' => [['code' => 'decimal']], 'base' => ['path' => 'RangeType.score']],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('score')->getAttributes(Range::class);
        self::assertNotEmpty($attrs, '#[Range] must be emitted when minValue/maxValue are present');
        $instance = $attrs[0]->newInstance();
        self::assertSame('0', $instance->min, 'Range::$min must be 0');
        self::assertSame('100', $instance->max, 'Range::$max must be 100');
    }

    public function testRangeNotEmittedWhenAbsent(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('NoRangeType', 'complex-type', [
                ['path' => 'NoRangeType.score', 'min' => 0, 'max' => '1', 'type' => [['code' => 'decimal']], 'base' => ['path' => 'NoRangeType.score']],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('score')->getAttributes(Range::class);
        self::assertEmpty($attrs, '#[Range] must NOT be emitted when minValue/maxValue are absent');
    }

    // -------------------------------------------------------------------------
    // Regex constraint (primitive .value element with type extension)
    // -------------------------------------------------------------------------

    public function testRegexEmittedFromTypeExtension(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('RegexType', 'primitive-type', [
                [
                    'path' => 'RegexType.value',
                    'min'  => 0,
                    'max'  => '1',
                    'type' => [[
                        'code'      => 'http://hl7.org/fhirpath/System.String',
                        'extension' => [
                            ['url' => 'http://hl7.org/fhir/StructureDefinition/regex', 'valueString' => '^[A-Z]+$'],
                        ],
                    ]],
                    'base'           => ['path' => 'RegexType.value'],
                    'representation' => ['xmlAttr'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\Primitive');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('value')->getAttributes(Regex::class);
        self::assertNotEmpty($attrs, '#[Regex] must be emitted when type has regex extension');
        self::assertSame('^[A-Z]+$', $attrs[0]->newInstance()->pattern, 'Regex::$pattern must match valueString');
    }

    public function testRegexNotEmittedWhenExtensionAbsent(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('NoRegexType', 'primitive-type', [
                ['path' => 'NoRegexType.value', 'min' => 0, 'max' => '1', 'type' => [['code' => 'http://hl7.org/fhirpath/System.String']], 'base' => ['path' => 'NoRegexType.value'], 'representation' => ['xmlAttr']],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\Primitive');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('value')->getAttributes(Regex::class);
        self::assertEmpty($attrs, '#[Regex] must NOT be emitted when type has no regex extension');
    }

    // -------------------------------------------------------------------------
    // FHIRValueSetBinding constraint
    // -------------------------------------------------------------------------

    public function testValueSetBindingEmittedForRequiredBinding(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('BindingType', 'complex-type', [
                [
                    'path'    => 'BindingType.status',
                    'min'     => 0,
                    'max'     => '1',
                    'type'    => [['code' => 'code']],
                    'binding' => ['strength' => 'required', 'valueSet' => 'http://hl7.org/fhir/ValueSet/publication-status'],
                    'base'    => ['path' => 'BindingType.status'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('status')->getAttributes(FHIRValueSetBinding::class);
        self::assertNotEmpty($attrs, '#[FHIRValueSetBinding] must be emitted for required binding');
        $instance = $attrs[0]->newInstance();
        self::assertSame('http://hl7.org/fhir/ValueSet/publication-status', $instance->valueSetUrl);
        self::assertSame('required', $instance->strength);
    }

    public function testValueSetBindingNotEmittedForExtensibleBinding(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('ExtensibleBindingType', 'complex-type', [
                [
                    'path'    => 'ExtensibleBindingType.status',
                    'min'     => 0,
                    'max'     => '1',
                    'type'    => [['code' => 'code']],
                    'binding' => ['strength' => 'extensible', 'valueSet' => 'http://hl7.org/fhir/ValueSet/something'],
                    'base'    => ['path' => 'ExtensibleBindingType.status'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('status')->getAttributes(FHIRValueSetBinding::class);
        self::assertEmpty($attrs, '#[FHIRValueSetBinding] must NOT be emitted for extensible binding');
    }

    // -------------------------------------------------------------------------
    // FHIRFixedValue constraint
    // -------------------------------------------------------------------------

    public function testFixedValueEmitted(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('FixedValueType', 'complex-type', [
                [
                    'path'        => 'FixedValueType.code',
                    'min'         => 0,
                    'max'         => '1',
                    'fixedString' => 'active',
                    'type'        => [['code' => 'http://hl7.org/fhirpath/System.String']],
                    'base'        => ['path' => 'FixedValueType.code'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('code')->getAttributes(FHIRFixedValue::class);
        self::assertNotEmpty($attrs, '#[FHIRFixedValue] must be emitted when fixedX is present');
        self::assertSame('active', $attrs[0]->newInstance()->value);
    }

    public function testFixedValueNotEmittedWhenAbsent(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('NoFixedValueType', 'complex-type', [
                ['path' => 'NoFixedValueType.code', 'min' => 0, 'max' => '1', 'type' => [['code' => 'http://hl7.org/fhirpath/System.String']], 'base' => ['path' => 'NoFixedValueType.code']],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('code')->getAttributes(FHIRFixedValue::class);
        self::assertEmpty($attrs, '#[FHIRFixedValue] must NOT be emitted when fixedX is absent');
    }

    // -------------------------------------------------------------------------
    // FHIRPatternValue constraint
    // -------------------------------------------------------------------------

    public function testPatternValueEmittedWithArrayPattern(): void
    {
        $class = $this->generator->generateModelClass(
            $this->buildSD('PatternValueType', 'complex-type', [
                [
                    'path'                   => 'PatternValueType.category',
                    'min'                    => 0,
                    'max'                    => '1',
                    'patternCodeableConcept' => ['coding' => [['system' => 'http://example.org', 'code' => 'vitals']]],
                    'type'                   => [['code' => 'CodeableConcept']],
                    'base'                   => ['path' => 'PatternValueType.category'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getProperty('category')->getAttributes(FHIRPatternValue::class);
        self::assertNotEmpty($attrs, '#[FHIRPatternValue] must be emitted when patternX is present');
        $instance = $attrs[0]->newInstance();
        self::assertSame(['coding' => [['system' => 'http://example.org', 'code' => 'vitals']]], $instance->pattern);
    }

    // -------------------------------------------------------------------------
    // FHIRPathInvariant (class-level, from element[0].constraint)
    // -------------------------------------------------------------------------

    public function testPathInvariantEmittedOnClassFromRootElementConstraints(): void
    {
        $sd    = $this->buildSDWithInvariants('InvariantType', 'complex-type', [
            [
                'key'        => 'inv-1',
                'severity'   => 'error',
                'human'      => 'Name must be present',
                'expression' => 'name.exists()',
            ],
        ]);
        $class = $this->generator->generateModelClass($sd, 'R4', $this->context);

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getAttributes(FHIRPathInvariant::class);
        self::assertNotEmpty($attrs, '#[FHIRPathInvariant] must be emitted on class for root element constraints');
        $instance = $attrs[0]->newInstance();
        self::assertSame('inv-1', $instance->key);
        self::assertSame('error', $instance->severity);
        self::assertSame('name.exists()', $instance->expression);
        self::assertSame('Name must be present', $instance->human);
    }

    public function testMultipleInvariantsAreRepeatable(): void
    {
        $sd = $this->buildSDWithInvariants('MultiInvariantType', 'complex-type', [
            ['key' => 'inv-1', 'severity' => 'error', 'human' => 'Inv1', 'expression' => 'name.exists()'],
            ['key' => 'inv-2', 'severity' => 'warning', 'human' => 'Inv2', 'expression' => 'value.exists()'],
        ]);
        $class = $this->generator->generateModelClass($sd, 'R4', $this->context);

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getAttributes(FHIRPathInvariant::class);
        self::assertCount(2, $attrs, 'Both invariants must be emitted as repeatable attributes');
    }

    // -------------------------------------------------------------------------
    // FHIRPathInvariant — skip when expression is absent or empty
    // -------------------------------------------------------------------------

    public function testInvariantSkippedWhenExpressionAbsent(): void
    {
        $sd = $this->buildSDWithInvariants('NoExprInvariantType', 'complex-type', [
            ['key' => 'inv-1', 'severity' => 'error', 'human' => 'XPath only', 'xpath' => 'f:name'],
        ]);
        $class = $this->generator->generateModelClass($sd, 'R4', $this->context);

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getAttributes(FHIRPathInvariant::class);
        self::assertEmpty($attrs, '#[FHIRPathInvariant] must NOT be emitted when expression is absent');
    }

    public function testInvariantSkippedWhenExpressionEmpty(): void
    {
        $sd = $this->buildSDWithInvariants('EmptyExprInvariantType', 'complex-type', [
            ['key' => 'inv-1', 'severity' => 'error', 'human' => 'Empty expr', 'expression' => ''],
        ]);
        $class = $this->generator->generateModelClass($sd, 'R4', $this->context);

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getAttributes(FHIRPathInvariant::class);
        self::assertEmpty($attrs, '#[FHIRPathInvariant] must NOT be emitted when expression is empty string');
    }

    // -------------------------------------------------------------------------
    // FHIRPathInvariant — source deduplication
    // -------------------------------------------------------------------------

    public function testInvariantSkippedWhenSourceIsParentUrl(): void
    {
        $sd = $this->buildSDWithInvariants('InheritedInvariantType', 'complex-type', [
            [
                'key'        => 'ele-1',
                'severity'   => 'error',
                'human'      => 'All FHIR elements must have a @value or children',
                'expression' => 'hasValue() or (children().count() > id.count())',
                'source'     => 'http://hl7.org/fhir/StructureDefinition/Element',
            ],
        ]);
        $class = $this->generator->generateModelClass($sd, 'R4', $this->context);

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getAttributes(FHIRPathInvariant::class);
        self::assertEmpty($attrs, '#[FHIRPathInvariant] must NOT be emitted for inherited invariants (source ≠ current SD url)');
    }

    public function testInvariantEmittedWhenSourceMatchesCurrentSdUrl(): void
    {
        $sdUrl = 'http://hl7.org/fhir/StructureDefinition/OwnSourceType';
        $sd    = $this->buildSDWithInvariants('OwnSourceType', 'complex-type', [
            [
                'key'        => 'own-1',
                'severity'   => 'error',
                'human'      => 'Own invariant',
                'expression' => 'name.exists()',
                'source'     => $sdUrl,
            ],
        ], $sdUrl);
        $class = $this->generator->generateModelClass($sd, 'R4', $this->context);

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getAttributes(FHIRPathInvariant::class);
        self::assertNotEmpty($attrs, '#[FHIRPathInvariant] must be emitted when source matches current SD URL');
    }

    public function testInvariantEmittedWhenSourceAbsent(): void
    {
        $sd    = $this->buildSDWithInvariants('NoSourceType', 'complex-type', [
            ['key' => 'ns-1', 'severity' => 'warning', 'human' => 'No source', 'expression' => 'value.exists()'],
        ]);
        $class = $this->generator->generateModelClass($sd, 'R4', $this->context);

        $fqcn  = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $attrs = (new \ReflectionClass($fqcn))->getAttributes(FHIRPathInvariant::class);
        self::assertNotEmpty($attrs, '#[FHIRPathInvariant] must be emitted when source is absent');
    }

    // -------------------------------------------------------------------------
    // createForElement — backbone child class invariant emission
    // -------------------------------------------------------------------------

    public function testPathInvariantEmittedOnBackboneChildClassFromElementConstraints(): void
    {
        // Build an SD with a backbone element that carries its own constraint.
        // The backbone element must have at least one child so nestElements() assigns
        // _properties, which triggers the child-class creation path in createForElement().
        $sd = $this->buildSD('BackboneContainerType', 'complex-type', [
            [
                'path'       => 'BackboneContainerType.item',
                'min'        => 0,
                'max'        => '*',
                'type'       => [['code' => 'BackboneElement']],
                'base'       => ['path' => 'BackboneContainerType.item'],
                'constraint' => [
                    ['key' => 'bb-1', 'severity' => 'error', 'human' => 'item must have a value', 'expression' => 'value.exists()'],
                ],
            ],
            [
                'path' => 'BackboneContainerType.item.value',
                'min'  => 0,
                'max'  => '1',
                'type' => [['code' => 'http://hl7.org/fhirpath/System.String']],
                'base' => ['path' => 'BackboneContainerType.item.value'],
            ],
        ]);

        $this->generator->generateModelClass($sd, 'R4', $this->context);

        // The child class is registered in context by its element path.
        $info = $this->context->getType('BackboneContainerType.item');
        self::assertNotNull($info, 'backbone child class must be registered in context');

        $childClass = $info->asClassType();
        $attrs      = $childClass->getAttributes();

        $invariantAttrs = array_filter($attrs, static fn (Attribute $a) => $a->getName() === FHIRPathInvariant::class);
        self::assertNotEmpty($invariantAttrs, '#[FHIRPathInvariant] must be emitted on backbone child class');

        $first = array_values($invariantAttrs)[0];
        self::assertSame('bb-1', $first->getArguments()['key'], 'invariant key must be bb-1');
        self::assertSame('value.exists()', $first->getArguments()['expression'], 'invariant expression must match');
    }

    // -------------------------------------------------------------------------
    // contentReference bail-out — value constraints must be skipped
    // -------------------------------------------------------------------------

    public function testConstraintsSkippedForContentReferenceElement(): void
    {
        // Pre-register a stub type so the contentReference lookup doesn't throw.
        $stubClass = new ClassType('ContentRefType');
        $this->context->addType('ContentRefType', self::TEST_NS . '\\DataType', $stubClass);

        $class = $this->generator->generateModelClass(
            $this->buildSD('ContentRefType', 'complex-type', [
                [
                    'path'             => 'ContentRefType.item',
                    'min'              => 0,
                    'max'              => '1',
                    'contentReference' => '#ContentRefType',
                    'base'             => ['path' => 'ContentRefType.item'],
                    'maxLength'        => 64,
                    'fixedString'      => 'active',
                    'binding'          => ['strength' => 'required', 'valueSet' => 'http://example.org/vs'],
                ],
            ]),
            'R4',
            $this->context,
        );

        $fqcn = $this->evalClass($class, self::TEST_NS . '\\DataType');
        $ref  = new \ReflectionClass($fqcn);

        self::assertEmpty($ref->getProperty('item')->getAttributes(Length::class), 'Length must not appear on contentReference element');
        self::assertEmpty($ref->getProperty('item')->getAttributes(FHIRFixedValue::class), 'FHIRFixedValue must not appear on contentReference element');
        self::assertEmpty($ref->getProperty('item')->getAttributes(FHIRValueSetBinding::class), 'FHIRValueSetBinding must not appear on contentReference element');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    /**
     * Build a minimal StructureDefinition with the given snapshot property elements.
     *
     * @param array<int, array<string, mixed>> $propertyElements
     *
     * @return array<string, mixed>
     */
    private function buildSD(string $name, string $kind, array $propertyElements): array
    {
        $rootElement = [
            'path' => $name,
            'min'  => 0,
            'max'  => '1',
            'base' => ['path' => $name],
        ];

        return [
            'resourceType' => 'StructureDefinition',
            'url'          => 'http://hl7.org/fhir/StructureDefinition/' . $name,
            'name'         => $name,
            'kind'         => $kind,
            'abstract'     => false,
            'snapshot'     => [
                'element' => array_merge([$rootElement], $propertyElements),
            ],
        ];
    }

    /**
     * Build a StructureDefinition whose root element carries the given constraint entries.
     *
     * @param array<int, array<string, mixed>> $constraints
     *
     * @return array<string, mixed>
     */
    private function buildSDWithInvariants(string $name, string $kind, array $constraints, ?string $url = null): array
    {
        $sdUrl = $url ?? ('http://hl7.org/fhir/StructureDefinition/' . $name);

        return [
            'resourceType' => 'StructureDefinition',
            'url'          => $sdUrl,
            'name'         => $name,
            'kind'         => $kind,
            'abstract'     => false,
            'snapshot'     => [
                'element' => [
                    [
                        'path'       => $name,
                        'min'        => 0,
                        'max'        => '1',
                        'base'       => ['path' => $name],
                        'constraint' => $constraints,
                    ],
                ],
            ],
        ];
    }

    private function evalClass(ClassType $class, string $namespace): string
    {
        $printer = new Printer();
        $nsObj   = new PhpNamespace($namespace);
        $code    = "<?php declare(strict_types=1);\n\nnamespace {$namespace};\n\n"
            . $printer->printClass($class, $nsObj);
        eval(substr($code, 5));

        return $namespace . '\\' . $class->getName();
    }
}

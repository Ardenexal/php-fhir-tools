<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\LogicalModelGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PromotedParameter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ardenexal\FHIRTools\Component\CodeGeneration\Generator\LogicalModelGenerator
 */
final class LogicalModelGeneratorTest extends TestCase
{
    private const string DT_NS = 'Ardenexal\\FHIRTools\\Component\\CdaModels\\DataType';

    private LogicalModelGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new LogicalModelGenerator();
    }

    /**
     * @param array<string, mixed>  $definition
     * @param array<string, string> $urlToFqcn
     * @param list<string>          $inheritedNames
     * @param array<string, string> $valueSetToEnumFqcn
     */
    private function generate(array $definition, array $urlToFqcn = [], array $inheritedNames = [], array $valueSetToEnumFqcn = []): ClassType
    {
        return $this->generator->generate(
            $definition,
            new PhpNamespace(self::DT_NS),
            'urn:hl7-org:v3',
            $urlToFqcn,
            $inheritedNames,
            [],
            $valueSetToEnumFqcn,
        );
    }

    /** @return array<string, mixed> */
    private function fhirPropertyArgs(ClassType $class, string $parameterName): array
    {
        $parameter = $class->getMethod('__construct')->getParameters()[$parameterName] ?? null;
        self::assertInstanceOf(PromotedParameter::class, $parameter);

        foreach ($parameter->getAttributes() as $attribute) {
            if ($attribute->getName() === FhirProperty::class) {
                return $attribute->getArguments();
            }
        }

        self::fail("No FhirProperty attribute on parameter {$parameterName}");
    }

    public function testGeneratesAbstractRootWithLogicalModelAttributeAndNoParent(): void
    {
        $class = $this->generate([
            'url'            => 'http://hl7.org/cda/stds/core/StructureDefinition/ANY',
            'name'           => 'ANY',
            'kind'           => 'logical',
            'derivation'     => 'specialization',
            'fhirVersion'    => '5.0.0',
            'abstract'       => true,
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Base',
            'snapshot'       => ['element' => [
                ['path' => 'ANY'],
                ['path' => 'ANY.nullFlavor', 'min' => 0, 'max' => '1', 'representation' => ['xmlAttr'], 'type' => [['code' => 'code']]],
            ]],
        ]);

        self::assertSame('ANY', $class->getName());
        self::assertTrue($class->isAbstract());
        self::assertNull($class->getExtends(), 'ANY has no generated CDA parent (base is FHIR Base)');

        $attributes = $class->getAttributes();
        self::assertCount(1, $attributes);
        self::assertSame(LogicalModel::class, $attributes[0]->getName());
        $args = $attributes[0]->getArguments();
        self::assertSame('ANY', $args['name']);
        self::assertSame('5.0.0', $args['fhirVersion']);
        self::assertSame('urn:hl7-org:v3', $args['xmlNamespace']);

        $args = $this->fhirPropertyArgs($class, 'nullFlavor');
        self::assertSame('@nullFlavor', $args['xmlSerializedName']);
    }

    public function testResolvesParentFromBaseDefinition(): void
    {
        $class = $this->generate(
            [
                'url'            => 'http://hl7.org/cda/stds/core/StructureDefinition/II',
                'name'           => 'II',
                'kind'           => 'logical',
                'derivation'     => 'specialization',
                'baseDefinition' => 'http://hl7.org/cda/stds/core/StructureDefinition/ANY',
                'snapshot'       => ['element' => [['path' => 'II']]],
            ],
            ['http://hl7.org/cda/stds/core/StructureDefinition/ANY' => '\\' . self::DT_NS . '\\ANY'],
        );

        self::assertFalse($class->isAbstract());
        self::assertSame(self::DT_NS . '\\ANY', ltrim((string) $class->getExtends(), '\\'));
    }

    public function testFixedScalarBecomesDefaultAndXmlAttr(): void
    {
        $class = $this->generate([
            'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
            'name'       => 'ClinicalDocument',
            'kind'       => 'logical',
            'derivation' => 'specialization',
            'snapshot'   => ['element' => [
                ['path' => 'ClinicalDocument'],
                ['path' => 'ClinicalDocument.classCode', 'min' => 0, 'max' => '1', 'representation' => ['xmlAttr'], 'type' => [['code' => 'code']], 'fixedCode' => 'DOCCLIN'],
            ]],
        ]);

        $parameter = $class->getMethod('__construct')->getParameters()['classCode'];
        self::assertSame('DOCCLIN', $parameter->getDefaultValue());
        self::assertSame('string', (string) $parameter->getType());

        $args = $this->fhirPropertyArgs($class, 'classCode');
        self::assertSame('@classCode', $args['xmlSerializedName']);
    }

    public function testCodePropertyWithBindingIsTypedToEnum(): void
    {
        $enumFqcn = '\\Ardenexal\\FHIRTools\\Component\\CdaModels\\Enum\\NullFlavor';
        $class    = $this->generate(
            [
                'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/ANY',
                'name'       => 'ANY',
                'kind'       => 'logical',
                'derivation' => 'specialization',
                'snapshot'   => ['element' => [
                    ['path' => 'ANY'],
                    [
                        'path'           => 'ANY.nullFlavor',
                        'min'            => 0,
                        'max'            => '1',
                        'representation' => ['xmlAttr'],
                        'type'           => [['code' => 'code']],
                        'binding'        => ['strength' => 'required', 'valueSet' => 'http://hl7.org/cda/stds/core/ValueSet/CDANullFlavor'],
                    ],
                ]],
            ],
            [],
            [],
            ['http://hl7.org/cda/stds/core/ValueSet/CDANullFlavor' => $enumFqcn],
        );

        $parameter = $class->getMethod('__construct')->getParameters()['nullFlavor'];
        self::assertSame($enumFqcn, '\\' . ltrim((string) $parameter->getType(), '\\'));
        self::assertTrue($parameter->isNullable());
        self::assertNull($parameter->getDefaultValue());

        $args = $this->fhirPropertyArgs($class, 'nullFlavor');
        self::assertSame('enum', $args['propertyKind']);
        self::assertSame('code', $args['fhirType']);
        self::assertSame('@nullFlavor', $args['xmlSerializedName']);
    }

    public function testArrayCodePropertyWithBindingIsTypedToEnumList(): void
    {
        $enumFqcn = '\\Ardenexal\\FHIRTools\\Component\\CdaModels\\Enum\\PostalAddressUse';
        $class    = $this->generate(
            [
                'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/AD',
                'name'       => 'AD',
                'kind'       => 'logical',
                'derivation' => 'specialization',
                'snapshot'   => ['element' => [
                    ['path' => 'AD'],
                    [
                        'path'    => 'AD.use',
                        'min'     => 0,
                        'max'     => '*',
                        'type'    => [['code' => 'code']],
                        'binding' => ['strength' => 'required', 'valueSet' => 'http://hl7.org/cda/stds/core/ValueSet/CDAPostalAddressUse'],
                    ],
                ]],
            ],
            [],
            [],
            ['http://hl7.org/cda/stds/core/ValueSet/CDAPostalAddressUse' => $enumFqcn],
        );

        $parameter = $class->getMethod('__construct')->getParameters()['use'];
        self::assertSame('array', (string) $parameter->getType());

        $args = $this->fhirPropertyArgs($class, 'use');
        self::assertTrue($args['isArray']);
        self::assertSame('enum', $args['propertyKind']);
        self::assertSame($enumFqcn, $args['phpType']);
    }

    public function testFixedCodePropertyKeepsScalarDefaultEvenWithBinding(): void
    {
        // A coded attribute with a fixed value keeps its string default; the fixed code is the
        // source of truth, so it is NOT re-typed to the enum (mapping fixed code → case deferred).
        $class = $this->generate(
            [
                'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
                'name'       => 'ClinicalDocument',
                'kind'       => 'logical',
                'derivation' => 'specialization',
                'snapshot'   => ['element' => [
                    ['path' => 'ClinicalDocument'],
                    [
                        'path'      => 'ClinicalDocument.classCode',
                        'min'       => 0,
                        'max'       => '1',
                        'type'      => [['code' => 'code']],
                        'fixedCode' => 'DOCCLIN',
                        'binding'   => ['strength' => 'required', 'valueSet' => 'http://hl7.org/cda/stds/core/ValueSet/CDAActClass'],
                    ],
                ]],
            ],
            [],
            [],
            ['http://hl7.org/cda/stds/core/ValueSet/CDAActClass' => '\\Ardenexal\\FHIRTools\\Component\\CdaModels\\Enum\\ActClass'],
        );

        $parameter = $class->getMethod('__construct')->getParameters()['classCode'];
        self::assertSame('DOCCLIN', $parameter->getDefaultValue());
        self::assertSame('string', (string) $parameter->getType());
        self::assertSame('scalar', $this->fhirPropertyArgs($class, 'classCode')['propertyKind']);
    }

    public function testCodePropertyWithUnbundledBindingStaysString(): void
    {
        // Binding to a ValueSet that is not in the generated-enum map (e.g. external v3
        // terminology) → property stays a plain nullable string.
        $class = $this->generate([
            'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/X',
            'name'       => 'X',
            'kind'       => 'logical',
            'derivation' => 'specialization',
            'snapshot'   => ['element' => [
                ['path' => 'X'],
                [
                    'path'    => 'X.code',
                    'min'     => 0,
                    'max'     => '1',
                    'type'    => [['code' => 'code']],
                    'binding' => ['strength' => 'required', 'valueSet' => 'http://terminology.hl7.org/ValueSet/v3-ActCode'],
                ],
            ]],
        ]);

        $parameter = $class->getMethod('__construct')->getParameters()['code'];
        self::assertSame('string', (string) $parameter->getType());
        self::assertTrue($parameter->isNullable());
        self::assertSame('scalar', $this->fhirPropertyArgs($class, 'code')['propertyKind']);
    }

    public function testArrayComplexPropertyIsTypedAsArrayWithItemFqcn(): void
    {
        $iiFqcn = '\\' . self::DT_NS . '\\II';
        $class  = $this->generate(
            [
                'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
                'name'       => 'ClinicalDocument',
                'kind'       => 'logical',
                'derivation' => 'specialization',
                'snapshot'   => ['element' => [
                    ['path' => 'ClinicalDocument'],
                    ['path' => 'ClinicalDocument.templateId', 'min' => 0, 'max' => '*', 'type' => [['code' => 'http://hl7.org/cda/stds/core/StructureDefinition/II']]],
                ]],
            ],
            ['http://hl7.org/cda/stds/core/StructureDefinition/II' => $iiFqcn],
        );

        $parameter = $class->getMethod('__construct')->getParameters()['templateId'];
        self::assertSame('array', (string) $parameter->getType());
        self::assertSame([], $parameter->getDefaultValue());

        $args = $this->fhirPropertyArgs($class, 'templateId');
        self::assertTrue($args['isArray']);
        self::assertSame('complex', $args['propertyKind']);
        self::assertSame($iiFqcn, $args['phpType']);
    }

    public function testRequiredSingleComplexPropertyIsNullableObject(): void
    {
        $iiFqcn = '\\' . self::DT_NS . '\\II';
        $class  = $this->generate(
            [
                'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
                'name'       => 'ClinicalDocument',
                'kind'       => 'logical',
                'derivation' => 'specialization',
                'snapshot'   => ['element' => [
                    ['path' => 'ClinicalDocument'],
                    ['path' => 'ClinicalDocument.id', 'min' => 1, 'max' => '1', 'type' => [['code' => 'http://hl7.org/cda/stds/core/StructureDefinition/II']]],
                ]],
            ],
            ['http://hl7.org/cda/stds/core/StructureDefinition/II' => $iiFqcn],
        );

        $parameter = $class->getMethod('__construct')->getParameters()['id'];
        self::assertTrue($parameter->isNullable());
        self::assertSame($iiFqcn, '\\' . ltrim((string) $parameter->getType(), '\\'));

        $args = $this->fhirPropertyArgs($class, 'id');
        self::assertTrue($args['isRequired']);
        self::assertFalse($args['isArray']);
    }

    public function testInheritedPropertyIsSkipped(): void
    {
        $class = $this->generate(
            [
                'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/II',
                'name'       => 'II',
                'kind'       => 'logical',
                'derivation' => 'specialization',
                'snapshot'   => ['element' => [
                    ['path' => 'II'],
                    ['path' => 'II.nullFlavor', 'min' => 0, 'max' => '1', 'representation' => ['xmlAttr'], 'type' => [['code' => 'code']]],
                    ['path' => 'II.root', 'min' => 0, 'max' => '1', 'representation' => ['xmlAttr'], 'type' => [['code' => 'string']]],
                ]],
            ],
            [],
            ['nullFlavor'],
        );

        $parameters = $class->getMethod('__construct')->getParameters();
        self::assertArrayNotHasKey('nullFlavor', $parameters, 'inherited property must not be re-declared');
        self::assertArrayHasKey('root', $parameters);
    }

    public function testReservedTypeNameGetsTypeSuffix(): void
    {
        $class = $this->generate([
            'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/INT',
            'name'       => 'INT',
            'kind'       => 'logical',
            'derivation' => 'specialization',
            'snapshot'   => ['element' => [['path' => 'INT']]],
        ]);

        self::assertSame('INTType', $class->getName());
    }

    public function testEmitsFhirPathInvariantFromRootConstraint(): void
    {
        $class = $this->generate([
            'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            'name'       => 'II',
            'kind'       => 'logical',
            'derivation' => 'specialization',
            'snapshot'   => ['element' => [
                ['path' => 'II', 'constraint' => [
                    ['key' => 'II-1', 'severity' => 'error', 'human' => 'must have root or nullFlavor', 'expression' => 'root.exists() or nullFlavor.exists()'],
                    // XPath-only constraint (no expression) must be skipped
                    ['key' => 'II-xpath', 'severity' => 'error', 'human' => 'x', 'xpath' => '@root|@nullFlavor'],
                ]],
            ]],
        ]);

        $invariants = $this->invariantAttributes($class);
        self::assertCount(1, $invariants, 'only the FHIRPath constraint is emitted; XPath-only is skipped');
        self::assertSame('II-1', $invariants[0]['key']);
        self::assertSame('root.exists() or nullFlavor.exists()', $invariants[0]['expression']);
        self::assertSame('error', $invariants[0]['severity']);
    }

    public function testSkipsInheritedConstraintKey(): void
    {
        $definition = [
            'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
            'name'       => 'ADXP',
            'kind'       => 'logical',
            'derivation' => 'specialization',
            'snapshot'   => ['element' => [
                ['path' => 'ADXP', 'constraint' => [
                    ['key' => 'text-null', 'severity' => 'error', 'human' => 'inherited from ST', 'expression' => 'text.exists() or nullFlavor.exists()'],
                ]],
            ]],
        ];

        // ADXP inlines ST's 'text-null' in its snapshot; passing it as inherited must suppress it.
        $class = $this->generator->generate($definition, new PhpNamespace(self::DT_NS), 'urn:hl7-org:v3', [], [], ['text-null']);

        self::assertCount(0, $this->invariantAttributes($class), 'inherited constraint key must not be re-emitted');
    }

    /** @return list<array<string, mixed>> */
    private function invariantAttributes(ClassType $class): array
    {
        $out = [];
        foreach ($class->getAttributes() as $attribute) {
            if ($attribute->getName() === FHIRPathInvariant::class) {
                $out[] = $attribute->getArguments();
            }
        }

        return $out;
    }

    public function testPropertyNameFromPath(): void
    {
        self::assertSame('realmCode', LogicalModelGenerator::propertyNameFromPath('ClinicalDocument.realmCode'));
        self::assertSame('root', LogicalModelGenerator::propertyNameFromPath('II.root'));
        self::assertSame('value', LogicalModelGenerator::propertyNameFromPath('Observation.value[x]'));
    }

    public function testResolvesParentFromTypeWhenTypeNamesADifferentClass(): void
    {
        // AU au-ClinicalDocument: `type` points at the core ClinicalDocument it specializes while
        // `baseDefinition` points at the abstract ANY root. The PHP parent must come from `type`.
        $class = $this->generate(
            [
                'url'            => 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-ClinicalDocument',
                'name'           => 'au-ClinicalDocument',
                'kind'           => 'logical',
                'derivation'     => 'specialization',
                'type'           => 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
                'baseDefinition' => 'http://hl7.org/cda/stds/core/StructureDefinition/ANY',
                'snapshot'       => ['element' => [['path' => 'au-ClinicalDocument']]],
            ],
            [
                'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument' => '\\' . self::DT_NS . '\\ClinicalDocument',
                'http://hl7.org/cda/stds/core/StructureDefinition/ANY'              => '\\' . self::DT_NS . '\\ANY',
            ],
        );

        self::assertSame(self::DT_NS . '\\ClinicalDocument', ltrim((string) $class->getExtends(), '\\'));
    }

    public function testTypeSeparatorMismatchFallsBackToBaseDefinition(): void
    {
        // Core's `type != url` cases are hyphen/underscore separator mismatches that name the SAME
        // type (not a different generatable class), so the parent must still come from baseDefinition.
        $class = $this->generate(
            [
                'url'            => 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-TS',
                'name'           => 'IVL_TS',
                'kind'           => 'logical',
                'derivation'     => 'specialization',
                'type'           => 'http://hl7.org/cda/stds/core/StructureDefinition/IVL_TS',
                'baseDefinition' => 'http://hl7.org/cda/stds/core/StructureDefinition/SXCM-TS',
                'snapshot'       => ['element' => [['path' => 'IVL-TS']]],
            ],
            ['http://hl7.org/cda/stds/core/StructureDefinition/SXCM-TS' => '\\' . self::DT_NS . '\\SXCMTS'],
        );

        self::assertSame(self::DT_NS . '\\SXCMTS', ltrim((string) $class->getExtends(), '\\'));
    }

    public function testForwardsInheritedParametersViaParentConstructor(): void
    {
        // A subclass must re-declare its parent's params as NON-promoted passthroughs and forward
        // them via parent::__construct() — otherwise the parent's promoted properties are never
        // initialised and throw on access.
        $parentSd = [
            'url'        => 'http://hl7.org/cda/stds/core/StructureDefinition/ANY',
            'name'       => 'ANY',
            'kind'       => 'logical',
            'derivation' => 'specialization',
            'snapshot'   => ['element' => [
                ['path' => 'ANY'],
                ['path' => 'ANY.nullFlavor', 'min' => 0, 'max' => '1', 'representation' => ['xmlAttr'], 'type' => [['code' => 'code']]],
            ]],
        ];
        $inheritedParams = $this->generator->collectOwnParameters($parentSd, [], 'urn:hl7-org:v3');

        $child = $this->generator->generate(
            [
                'url'            => 'http://hl7.org/cda/stds/core/StructureDefinition/II',
                'name'           => 'II',
                'kind'           => 'logical',
                'derivation'     => 'specialization',
                'baseDefinition' => 'http://hl7.org/cda/stds/core/StructureDefinition/ANY',
                'snapshot'       => ['element' => [
                    ['path' => 'II'],
                    ['path' => 'II.nullFlavor', 'min' => 0, 'max' => '1', 'representation' => ['xmlAttr'], 'type' => [['code' => 'code']]],
                    ['path' => 'II.root', 'min' => 0, 'max' => '1', 'representation' => ['xmlAttr'], 'type' => [['code' => 'string']]],
                ]],
            ],
            new PhpNamespace(self::DT_NS),
            'urn:hl7-org:v3',
            ['http://hl7.org/cda/stds/core/StructureDefinition/ANY' => '\\' . self::DT_NS . '\\ANY'],
            ['nullFlavor'],
            [],
            [],
            $inheritedParams,
        );

        $parameters = $child->getMethod('__construct')->getParameters();
        // Own property is promoted; inherited property is a non-promoted passthrough.
        self::assertInstanceOf(PromotedParameter::class, $parameters['root']);
        self::assertArrayHasKey('nullFlavor', $parameters);
        self::assertNotInstanceOf(PromotedParameter::class, $parameters['nullFlavor']);

        $body = (string) $child->getMethod('__construct')->getBody();
        self::assertStringContainsString('parent::__construct(', $body);
        self::assertStringContainsString('nullFlavor: $nullFlavor', $body);
    }
}

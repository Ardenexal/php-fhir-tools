<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\LogicalModelGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PromotedParameter;
use PHPUnit\Framework\TestCase;

/**
 * A CDA profile that rebinds an inherited coded element to a wider ValueSet (au-Participant2 binds
 * `typeCode` to the full v3 ParticipationType; core Participant2 uses the CDA subset) cannot retype
 * the inherited property, so the declaring class widens it to `Enum|string`. Regression cover for
 * issue #134, where the AU binding was silently dropped and `CAGNT` could not be carried.
 *
 * @covers \Ardenexal\FHIRTools\Component\CodeGeneration\Generator\LogicalModelGenerator
 */
final class LogicalModelReboundCodedPropertyTest extends TestCase
{
    private const string CORE = 'http://hl7.org/cda/stds/core/StructureDefinition/Participant2';

    private const string AU   = 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Participant2';

    private const string ENUM = '\\Ardenexal\\FHIRTools\\Component\\CdaModels\\Enum\\ParticipationType';

    private const string CDA_VS = 'http://hl7.org/cda/stds/core/ValueSet/CDAParticipationType';

    private const string V3_VS  = 'http://terminology.hl7.org/ValueSet/v3-ParticipationType';

    private LogicalModelGenerator $generator;

    protected function setUp(): void
    {
        $this->generator = new LogicalModelGenerator();
    }

    /**
     * @param array<string, mixed> $extra element keys merged into the typeCode element
     *
     * @return array<string, mixed>
     */
    private function participant(string $url, string $name, ?string $valueSet, array $extra = []): array
    {
        $element = ['path' => 'Participant2.typeCode', 'min' => 1, 'max' => '1', 'type' => [['code' => 'code']], 'representation' => ['xmlAttr']];
        if ($valueSet !== null) {
            $element['binding'] = ['strength' => 'required', 'valueSet' => $valueSet];
        }

        return [
            'url'      => $url,
            'name'     => $name,
            'snapshot' => ['element' => [['path' => 'Participant2'], $element + $extra]],
        ];
    }

    public function testRebindingInADescendantMarksTheDeclaringClass(): void
    {
        $rebound = $this->generator->findReboundCodedProperties(
            [
                self::CORE => $this->participant(self::CORE, 'Participant2', self::CDA_VS),
                self::AU   => $this->participant(self::AU, 'au-Participant2', self::V3_VS . '|3.0.0'),
            ],
            [self::AU => self::CORE],
        );

        self::assertSame([self::CORE => ['typeCode']], $rebound);
    }

    public function testSameValueSetIsNotARebinding(): void
    {
        $rebound = $this->generator->findReboundCodedProperties(
            [
                self::CORE => $this->participant(self::CORE, 'Participant2', self::CDA_VS),
                self::AU   => $this->participant(self::AU, 'au-Participant2', self::CDA_VS . '|2.0.2-sd'),
            ],
            [self::AU => self::CORE],
        );

        self::assertSame([], $rebound);
    }

    public function testFixedValueInTheDescendantIsNotARebinding(): void
    {
        $rebound = $this->generator->findReboundCodedProperties(
            [
                self::CORE => $this->participant(self::CORE, 'Participant2', self::CDA_VS),
                self::AU   => $this->participant(self::AU, 'au-Participant2', self::V3_VS, ['fixedCode' => 'CAGNT']),
            ],
            [self::AU => self::CORE],
        );

        self::assertSame([], $rebound);
    }

    public function testRebindingByAGrandchildMarksTheFarthestDeclaringAncestor(): void
    {
        $middle  = 'http://example.org/StructureDefinition/middle';
        $rebound = $this->generator->findReboundCodedProperties(
            [
                self::CORE => $this->participant(self::CORE, 'Participant2', self::CDA_VS),
                $middle    => $this->participant($middle, 'middle', self::CDA_VS),
                self::AU   => $this->participant(self::AU, 'au-Participant2', self::V3_VS),
            ],
            [$middle => self::CORE, self::AU => $middle],
        );

        self::assertSame([self::CORE => ['typeCode']], $rebound);
    }

    public function testUnboundRootAlreadyDeclaresAStringSoNothingWidens(): void
    {
        // The PHP property belongs to the farthest ancestor whose snapshot has the element, since
        // every descendant skips inherited names. An unbound root therefore declares it as a plain
        // string, and the middle class's binding never produces a strict property to widen.
        $middle  = 'http://example.org/StructureDefinition/middle';
        $root    = $this->participant(self::CORE, 'Participant2', null);
        $rebound = $this->generator->findReboundCodedProperties(
            [
                self::CORE => $root,
                $middle    => $this->participant($middle, 'middle', self::CDA_VS),
                self::AU   => $this->participant(self::AU, 'au-Participant2', self::V3_VS),
            ],
            [$middle => self::CORE, self::AU => $middle],
        );

        self::assertSame([], $rebound);
        self::assertSame('string', (string) $this->parameter($this->generate($root, []), 'typeCode')->getType());
    }

    public function testOpenCodedScalarIsTypedEnumOrString(): void
    {
        $class = $this->generate($this->participant(self::CORE, 'Participant2', self::CDA_VS), ['typeCode']);

        $parameter = $this->parameter($class, 'typeCode');
        self::assertSame(self::ENUM . '|string', (string) $parameter->getType());
        self::assertTrue($parameter->isNullable());
        self::assertSame('openEnum', $this->fhirPropertyArgs($parameter)['propertyKind']);
    }

    public function testUnmarkedCodedScalarStaysAStrictEnum(): void
    {
        $class = $this->generate($this->participant(self::CORE, 'Participant2', self::CDA_VS), []);

        $parameter = $this->parameter($class, 'typeCode');
        self::assertSame(self::ENUM, (string) $parameter->getType());
        self::assertSame('enum', $this->fhirPropertyArgs($parameter)['propertyKind']);
    }

    public function testOpenCodedListKeepsTheEnumAsItemClassAndWidensTheDocblock(): void
    {
        $definition                                  = $this->participant(self::CORE, 'Participant2', self::CDA_VS);
        $definition['snapshot']['element'][1]['max'] = '*';

        $class = $this->generate($definition, ['typeCode']);

        $parameter = $this->parameter($class, 'typeCode');
        self::assertSame('array', (string) $parameter->getType());
        $args = $this->fhirPropertyArgs($parameter);
        self::assertSame('openEnum', $args['propertyKind']);
        self::assertSame(self::ENUM, $args['phpType']);
        self::assertStringContainsString('@param list<' . self::ENUM . '|string> $typeCode', $class->getMethod('__construct')->getComment() ?? '');
    }

    /**
     * @param array<string, mixed> $definition
     * @param list<string>         $openCodedNames
     */
    private function generate(array $definition, array $openCodedNames): ClassType
    {
        return $this->generator->generate(
            $definition,
            new PhpNamespace('Ardenexal\\FHIRTools\\Component\\CdaModels\\ClinicalClass'),
            'urn:hl7-org:v3',
            [],
            valueSetToEnumFqcn: [self::CDA_VS => self::ENUM],
            openCodedNames: $openCodedNames,
        );
    }

    private function parameter(ClassType $class, string $name): PromotedParameter
    {
        $parameter = $class->getMethod('__construct')->getParameters()[$name] ?? null;
        self::assertInstanceOf(PromotedParameter::class, $parameter);

        return $parameter;
    }

    /** @return array<string, mixed> */
    private function fhirPropertyArgs(PromotedParameter $parameter): array
    {
        foreach ($parameter->getAttributes() as $attribute) {
            if ($attribute->getName() === FhirProperty::class) {
                return $attribute->getArguments();
            }
        }
        self::fail('No FhirProperty attribute');
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\CdaTypeHierarchy;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\LogicalModelGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PromotedParameter;
use PHPUnit\Framework\TestCase;

/**
 * An element admitting several datatypes must be generated as polymorphic.
 *
 * Before this, the generator kept `type[0]` and discarded the rest, so an observation's value — which
 * admits 29 datatypes — was typed to `CD` alone and could not hold the text value its own definition
 * permits.
 *
 * @covers \Ardenexal\FHIRTools\Component\CodeGeneration\Generator\LogicalModelGenerator
 */
final class LogicalModelPolymorphicElementTest extends TestCase
{
    private const string DT_NS = 'Ardenexal\\FHIRTools\\Component\\CdaModels\\DataType';

    private const string ANY = 'urn:test:ANY';

    private const string CD  = 'urn:test:CD';

    private const string CE  = 'urn:test:CE';

    private const string ST  = 'urn:test:ST';

    private const string PQ  = 'urn:test:PQ';

    private const string QTY = 'urn:test:QTY';

    /**
     * @param array<string, string> $extraNames
     * @param array<string, string> $extraParents
     */
    private function hierarchy(array $extraNames = [], array $extraParents = []): CdaTypeHierarchy
    {
        return new CdaTypeHierarchy(
            [
                self::ANY => 'ANY',
                self::CD  => 'CD',
                self::CE  => 'CE',
                self::ST  => 'ST',
                self::QTY => 'QTY',
                self::PQ  => 'PQ',
            ] + $extraNames,
            [
                self::ANY => '',
                self::CD  => self::ANY,
                self::CE  => self::CD,
                self::ST  => self::ANY,
                self::QTY => self::ANY,
                self::PQ  => self::QTY,
            ] + $extraParents,
        );
    }

    /**
     * @return array<string, string>
     */
    private function urlToFqcn(): array
    {
        return [
            self::ANY => '\\' . self::DT_NS . '\\ANY',
            self::CD  => '\\' . self::DT_NS . '\\CD',
            self::CE  => '\\' . self::DT_NS . '\\CE',
            self::ST  => '\\' . self::DT_NS . '\\ST',
            self::QTY => '\\' . self::DT_NS . '\\QTY',
            self::PQ  => '\\' . self::DT_NS . '\\PQ',
        ];
    }

    /**
     * @param list<array<string, mixed>> $valueTypes
     * @param array<string, string>      $urlToFqcn
     */
    private function generateWithValue(
        array $valueTypes,
        ?CdaTypeHierarchy $hierarchy,
        ?array $urlToFqcn = null,
        string $max = '*',
    ): ClassType {
        return (new LogicalModelGenerator())->generate(
            [
                'url'            => 'urn:test:Observation',
                'name'           => 'Observation',
                'kind'           => 'logical',
                'derivation'     => 'specialization',
                'fhirVersion'    => '5.0.0',
                'baseDefinition' => self::ANY,
                'snapshot'       => ['element' => [
                    ['path' => 'Observation'],
                    ['path' => 'Observation.value', 'min' => 0, 'max' => $max, 'type' => $valueTypes],
                ]],
            ],
            new PhpNamespace(self::DT_NS),
            'urn:hl7-org:v3',
            $urlToFqcn ?? $this->urlToFqcn(),
            [],
            [],
            [],
            [],
            [],
            $hierarchy,
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

    public function testAnElementAdmittingSeveralDatatypesBecomesPolymorphic(): void
    {
        $class = $this->generateWithValue(
            [['code' => self::CD], ['code' => self::ST], ['code' => self::PQ]],
            $this->hierarchy(),
        );

        $args = $this->fhirPropertyArgs($class, 'value');
        self::assertSame('polymorphic', $args['propertyKind']);
        self::assertArrayNotHasKey('isChoice', $args, 'CDA keeps the element name, so this is not a value[x] choice');
        self::assertCount(3, $args['variants']);
    }

    public function testTheElementIsTypedToTheNearestSharedDatatypeRatherThanTheFirstListed(): void
    {
        $class = $this->generateWithValue(
            [['code' => self::CD], ['code' => self::ST]],
            $this->hierarchy(),
        );

        // Not CD, which is merely the first entry the definition lists.
        self::assertSame('\\' . self::DT_NS . '\\ANY', $this->fhirPropertyArgs($class, 'value')['phpType']);
    }

    public function testTheSharedDatatypeIsNotWidenedPastWhatTheDefinitionAdmits(): void
    {
        // PQ and QTY both sit under QTY, so widening to ANY would admit ST — which this element forbids.
        $class = $this->generateWithValue(
            [['code' => self::QTY], ['code' => self::PQ]],
            $this->hierarchy(),
        );

        self::assertSame('\\' . self::DT_NS . '\\QTY', $this->fhirPropertyArgs($class, 'value')['phpType']);
    }

    public function testEveryVariantCarriesThePublishedTypeNameAndSharesTheElementName(): void
    {
        $class = $this->generateWithValue(
            [['code' => self::CD], ['code' => self::ST]],
            // A published name that no PHP class name or URL segment would produce.
            $this->hierarchy(
                ['urn:test:IVL-PQ' => 'IVL_PQ'],
                ['urn:test:IVL-PQ' => self::PQ],
            ),
            $this->urlToFqcn() + ['urn:test:IVL-PQ' => '\\' . self::DT_NS . '\\IVLPQ'],
        );

        $variants = $this->fhirPropertyArgs($class, 'value')['variants'];
        self::assertIsArray($variants);
        foreach ($variants as $variant) {
            self::assertSame('value', $variant['jsonKey'], 'CDA discriminates on an attribute, not the element name');
            self::assertNotSame('', $variant['typeName']);
        }
        self::assertSame(['CD', 'ST'], array_column($variants, 'typeName'));
    }

    public function testTheUnderscoredPublishedNameIsUsedRatherThanTheUrlSegment(): void
    {
        $class = $this->generateWithValue(
            [['code' => self::PQ], ['code' => 'urn:test:IVL-PQ']],
            $this->hierarchy(
                ['urn:test:IVL-PQ' => 'IVL_PQ'],
                ['urn:test:IVL-PQ' => self::PQ],
            ),
            $this->urlToFqcn() + ['urn:test:IVL-PQ' => '\\' . self::DT_NS . '\\IVLPQ'],
        );

        $names = array_column($this->fhirPropertyArgs($class, 'value')['variants'], 'typeName');
        self::assertContains('IVL_PQ', $names, 'the URL says IVL-PQ; only the definition says IVL_PQ');
        self::assertNotContains('IVL-PQ', $names);
    }

    public function testVariantsAreOrderedSubclassBeforeSuperclass(): void
    {
        // The definitions publish the opposite order — an observation's value lists CD before CE — and
        // a reader takes the first instanceof match, so emitting that order sends a CE out as a CD.
        $variants = $this->fhirPropertyArgs(
            $this->generateWithValue(
                [['code' => self::CD], ['code' => self::ST], ['code' => self::CE]],
                $this->hierarchy(),
            ),
            'value',
        )['variants'];

        $names = array_column($variants, 'typeName');
        self::assertLessThan(
            array_search('CD', $names, true),
            array_search('CE', $names, true),
            'CE derives from CD and must be tested first',
        );
    }

    public function testAnElementAdmittingOneDatatypeIsUnchanged(): void
    {
        $args = $this->fhirPropertyArgs(
            $this->generateWithValue([['code' => self::CD]], $this->hierarchy()),
            'value',
        );

        self::assertSame('complex', $args['propertyKind']);
        self::assertArrayNotHasKey('variants', $args);
    }

    public function testARepeatedTypeEntryDoesNotMakeAnElementPolymorphic(): void
    {
        $args = $this->fhirPropertyArgs(
            $this->generateWithValue([['code' => self::CD], ['code' => self::CD]], $this->hierarchy()),
            'value',
        );

        self::assertSame('complex', $args['propertyKind'], 'one distinct datatype is not a choice');
    }

    public function testWithoutAHierarchyTheGeneratorKeepsItsPreviousSingleTypeBehaviour(): void
    {
        $args = $this->fhirPropertyArgs(
            $this->generateWithValue([['code' => self::CD], ['code' => self::ST]], null),
            'value',
        );

        self::assertSame('complex', $args['propertyKind']);
    }

    public function testGenerationFailsRatherThanGuessAnUnnamedDatatype(): void
    {
        // The guard: a datatype the definitions do not name. Falling back to the URL segment or the
        // class name would emit a plausible, wrong discriminator that only schema validation catches.
        $this->expectException(GenerationException::class);
        $this->expectExceptionMessageMatches('/no generated class or published CDA type name could be resolved/');

        $this->generateWithValue(
            [['code' => self::CD], ['code' => 'urn:test:Unnamed']],
            $this->hierarchy(),
            $this->urlToFqcn() + ['urn:test:Unnamed' => '\\' . self::DT_NS . '\\Unnamed'],
        );
    }

    public function testGenerationFailsWhenAnAdmittedDatatypeHasNoGeneratedClass(): void
    {
        $this->expectException(GenerationException::class);
        $this->expectExceptionMessageMatches('/no generated class or published CDA type name could be resolved/');

        // Named in the hierarchy, but absent from the class map.
        $this->generateWithValue(
            [['code' => self::CD], ['code' => self::ST]],
            $this->hierarchy(),
            [self::ANY => '\\' . self::DT_NS . '\\ANY', self::CD => '\\' . self::DT_NS . '\\CD'],
        );
    }

    public function testGenerationFailsWhenAdmittedDatatypesShareNoCommonType(): void
    {
        $this->expectException(GenerationException::class);
        $this->expectExceptionMessageMatches('/share no common ancestor/');

        $this->generateWithValue(
            [['code' => 'urn:test:Rootless1'], ['code' => 'urn:test:Rootless2']],
            new CdaTypeHierarchy(
                ['urn:test:Rootless1' => 'R1', 'urn:test:Rootless2' => 'R2'],
                ['urn:test:Rootless1' => '', 'urn:test:Rootless2' => ''],
            ),
            [
                'urn:test:Rootless1' => '\\' . self::DT_NS . '\\R1',
                'urn:test:Rootless2' => '\\' . self::DT_NS . '\\R2',
            ],
        );
    }

    public function testANonRepeatingPolymorphicElementIsTypedToTheSharedDatatypeItself(): void
    {
        $class = $this->generateWithValue(
            [['code' => self::CD], ['code' => self::ST]],
            $this->hierarchy(),
            null,
            '1',
        );

        $parameter = $class->getMethod('__construct')->getParameters()['value'] ?? null;
        self::assertInstanceOf(PromotedParameter::class, $parameter);
        self::assertSame('\\' . self::DT_NS . '\\ANY', $parameter->getType());
    }
}

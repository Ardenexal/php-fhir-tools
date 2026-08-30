<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRValueSetGenerator;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRValueSetGenerator
 */
final class FHIRValueSetGeneratorTest extends TestCase
{
    private const string ENUM_NS = 'Ardenexal\\FHIRTools\\Component\\CdaModels\\Enum';

    private FHIRValueSetGenerator $generator;

    private BuilderContext $context;

    protected function setUp(): void
    {
        $this->generator = new FHIRValueSetGenerator();
        $this->context   = new BuilderContext();
        $this->context->addEnumNamespace('CDA', new PhpNamespace(self::ENUM_NS));
    }

    public function testClassNameOverrideIsUsedVerbatim(): void
    {
        $enum = $this->generator->generateEnum(
            [
                'resourceType' => 'ValueSet',
                'url'          => 'http://hl7.org/cda/stds/core/ValueSet/CDANullFlavor',
                'name'         => 'CDANullFlavor',
                'compose'      => ['include' => [[
                    'system'  => 'http://terminology.hl7.org/CodeSystem/v3-NullFlavor',
                    'concept' => [['code' => 'NI'], ['code' => 'UNK'], ['code' => 'ASKU']],
                ]]],
            ],
            'CDA',
            $this->context,
            'NullFlavor',
        );

        // The override drops the redundant `CDA` prefix the ValueSet name carries.
        self::assertSame('NullFlavor', $enum->getName());

        $values = array_values(array_map(static fn ($c) => $c->getValue(), $enum->getCases()));
        self::assertEqualsCanonicalizing(['NI', 'UNK', 'ASKU'], $values);
    }

    public function testSymbolicConceptCodesGetSluggedNamesAndNoEmptyCase(): void
    {
        // The CDA ObservationInterpretation ValueSet inlines the symbolic codes '<' and '>',
        // which snake() alone strips to an empty (invalid) identifier. The slugger fallback maps
        // them to word-based names so every case is valid.
        $enum = $this->generator->generateEnum(
            [
                'resourceType' => 'ValueSet',
                'url'          => 'http://hl7.org/cda/stds/core/ValueSet/CDAObservationInterpretation',
                'name'         => 'CDAObservationInterpretation',
                'compose'      => ['include' => [[
                    'system'  => 'http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation',
                    'concept' => [['code' => 'N'], ['code' => '<'], ['code' => '>']],
                ]]],
            ],
            'CDA',
            $this->context,
            'ObservationInterpretation',
        );

        $cases = $enum->getCases();
        self::assertCount(3, $cases);
        foreach ($cases as $case) {
            self::assertNotSame('', $case->getName(), 'enum case name must not be empty');
        }

        // Backing values are preserved exactly (the symbols), only the case *names* are slugged.
        $values = array_values(array_map(static fn ($c) => $c->getValue(), $cases));
        self::assertEqualsCanonicalizing(['N', '<', '>'], $values);
    }

    public function testDuplicateBackingValuesAreDeduplicated(): void
    {
        // The AU dh-entitynameuse ValueSet lists a single code under two display names (e.g.
        // 'enterprise name' and the bare code 'ORGE'), which would otherwise emit two enum cases
        // sharing the backing value 'ORGE' — invalid for a PHP backed enum.
        $enum = $this->generator->generateEnum(
            [
                'resourceType' => 'ValueSet',
                'url'          => 'http://ns.electronichealth.net.au/cda/ValueSet/dh-entitynameuse',
                'name'         => 'Entity Name Use',
                'compose'      => ['include' => [[
                    'system'  => 'http://terminology.hl7.org/CodeSystem/v3-EntityNameUse',
                    'concept' => [
                        ['code' => 'ORGE', 'display' => 'enterprise name'],
                        ['code' => 'ORGE', 'display' => 'ORGE'],
                        ['code' => 'ORGL', 'display' => 'locally used name'],
                    ],
                ]]],
            ],
            'CDA',
            $this->context,
            'AuDhEntitynameuse',
        );

        $values = array_values(array_map(static fn ($c) => $c->getValue(), $enum->getCases()));
        self::assertSame(['ORGE', 'ORGL'], $values, 'duplicate backing values must collapse to one case each');
    }
}

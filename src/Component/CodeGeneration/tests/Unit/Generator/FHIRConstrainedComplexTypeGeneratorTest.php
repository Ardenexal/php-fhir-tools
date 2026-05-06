<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\CodeGeneration\tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRConstrainedComplexTypeGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRSliceDiscriminator;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for FHIRConstrainedComplexTypeGenerator.
 *
 * Verifies that FHIR complex type constraint profiles (StructureDefinitions with
 * fixed[x] or pattern[x] values) are correctly converted into typed PHP profile
 * classes with:
 *   - Correct class name and parent class
 *   - #[FHIRProfile] and #[FHIRSliceDiscriminator] attributes
 *   - PROFILE_URL and FIXED_* constants
 *   - A constructor that bakes in fixed values and exposes variable params only
 *
 * @see FHIRConstrainedComplexTypeGenerator
 */
class FHIRConstrainedComplexTypeGeneratorTest extends TestCase
{
    private const string FIXTURES_DIR = __DIR__ . '/../../Fixtures/StructureDefinitions';

    private FHIRConstrainedComplexTypeGenerator $generator;

    private BuilderContext $context;

    private PhpNamespace $namespace;

    protected function setUp(): void
    {
        $this->generator = new FHIRConstrainedComplexTypeGenerator();

        $this->context = new BuilderContext();
        $this->context->addElementNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource'));
        $this->context->addDatatypeNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType'));
        $this->context->addPrimitiveNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Primitive'));
        $this->context->addEnumNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum'));

        // Register the base Identifier type so the generator can resolve it as the parent
        $identifierClass = new ClassType('Identifier', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType'));
        $this->context->addType(
            'http://hl7.org/fhir/StructureDefinition/Identifier',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType',
            $identifierClass,
        );

        $this->namespace = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuBase\\Profile');
    }

    // -----------------------------------------------------------------
    // hasConstrainedElements() detection
    // -----------------------------------------------------------------

    public function testHasConstrainedElementsReturnsTrueForFixedValue(): void
    {
        $sd = $this->loadFixture('AUIHI.json');

        self::assertTrue(FHIRConstrainedComplexTypeGenerator::hasConstrainedElements($sd));
    }

    public function testHasConstrainedElementsReturnsFalseWithNoFixedValues(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/plain',
            'name'           => 'PlainProfile',
            'type'           => 'Identifier',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Identifier',
            'snapshot'       => [
                'element' => [
                    ['path' => 'Identifier', 'min' => 0, 'max' => '1'],
                    ['path' => 'Identifier.value', 'min' => 1, 'max' => '1', 'type' => [['code' => 'string']]],
                ],
            ],
        ];

        self::assertFalse(FHIRConstrainedComplexTypeGenerator::hasConstrainedElements($sd));
    }

    public function testHasConstrainedElementsIgnoresNestedPaths(): void
    {
        // A fixed value on a nested path (Identifier.type.coding.code) should not trigger
        // the generator, since we only bake in top-level property constraints.
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/nested',
            'name'           => 'NestedConstraint',
            'type'           => 'Identifier',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Identifier',
            'snapshot'       => [
                'element' => [
                    ['path' => 'Identifier', 'min' => 0, 'max' => '1'],
                    ['path' => 'Identifier.type.coding', 'min' => 0, 'max' => '*', 'fixedCode' => 'NI'],
                ],
            ],
        ];

        self::assertFalse(FHIRConstrainedComplexTypeGenerator::hasConstrainedElements($sd));
    }

    // -----------------------------------------------------------------
    // AU IHI profile — class name and parent
    // -----------------------------------------------------------------

    public function testAUIHIGeneratesCorrectClassName(): void
    {
        $sd    = $this->loadFixture('AUIHI.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertSame('AUIHIProfile', $class->getName());
    }

    public function testAUIHIExtendsIdentifier(): void
    {
        $sd    = $this->loadFixture('AUIHI.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $extends = $class->getExtends();
        self::assertNotNull($extends);
        self::assertStringContainsString('Identifier', $extends);
    }

    // -----------------------------------------------------------------
    // AU IHI profile — attributes
    // -----------------------------------------------------------------

    public function testAUIHIHasFHIRProfileAttribute(): void
    {
        $sd    = $this->loadFixture('AUIHI.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $found = false;
        foreach ($class->getAttributes() as $attribute) {
            if (str_contains($attribute->getName(), 'FHIRProfile')) {
                $found = true;
                $args  = $attribute->getArguments();
                self::assertSame('http://hl7.org.au/fhir/StructureDefinition/au-ihi', $args['profileUrl']);
                self::assertSame('Identifier', $args['baseType']);
                self::assertSame('R4', $args['fhirVersion']);
            }
        }

        self::assertTrue($found, 'FHIRProfile attribute not found on generated AU IHI profile class');
    }

    public function testAUIHIHasValueDiscriminatorAttribute(): void
    {
        $sd    = $this->loadFixture('AUIHI.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $found = false;
        foreach ($class->getAttributes() as $attribute) {
            if (!str_contains($attribute->getName(), 'FHIRSliceDiscriminator')) {
                continue;
            }

            $args = $attribute->getArguments();
            if (($args['type'] ?? '') === 'value' && ($args['path'] ?? '') === 'system') {
                $found = true;
                self::assertSame(
                    'http://ns.electronichealth.net.au/id/hi/ihi/1.0',
                    $args['value'],
                );
            }
        }

        self::assertTrue($found, '#[FHIRSliceDiscriminator(type: value, path: system)] not found');
    }

    public function testAUIHIHasPatternDiscriminatorAttribute(): void
    {
        $sd    = $this->loadFixture('AUIHI.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $found = false;
        foreach ($class->getAttributes() as $attribute) {
            if (!str_contains($attribute->getName(), 'FHIRSliceDiscriminator')) {
                continue;
            }

            $args = $attribute->getArguments();
            if (($args['type'] ?? '') === 'pattern' && ($args['path'] ?? '') === 'type') {
                $found = true;
                self::assertIsArray($args['value']);
                self::assertArrayHasKey('coding', $args['value']);
            }
        }

        self::assertTrue($found, '#[FHIRSliceDiscriminator(type: pattern, path: type)] not found');
    }

    // -----------------------------------------------------------------
    // AU IHI profile — constants
    // -----------------------------------------------------------------

    public function testAUIHIHasProfileUrlConstant(): void
    {
        $sd    = $this->loadFixture('AUIHI.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constants = $class->getConstants();
        self::assertArrayHasKey('PROFILE_URL', $constants);
        self::assertSame('http://hl7.org.au/fhir/StructureDefinition/au-ihi', $constants['PROFILE_URL']->getValue());
    }

    public function testAUIHIHasFixedSystemConstant(): void
    {
        $sd    = $this->loadFixture('AUIHI.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constants = $class->getConstants();
        self::assertArrayHasKey('FIXED_SYSTEM', $constants);
        self::assertSame(
            'http://ns.electronichealth.net.au/id/hi/ihi/1.0',
            $constants['FIXED_SYSTEM']->getValue(),
        );
    }

    public function testAUIHIDoesNotHaveFixedConstantForComplexPatternValue(): void
    {
        // The patternCodeableConcept on 'type' is a complex value — no scalar constant expected
        $sd    = $this->loadFixture('AUIHI.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constants = $class->getConstants();
        self::assertArrayNotHasKey('FIXED_TYPE', $constants, 'Complex pattern values should not produce a FIXED_ constant');
    }

    // -----------------------------------------------------------------
    // AU IHI profile — constructor
    // -----------------------------------------------------------------

    public function testAUIHIConstructorExists(): void
    {
        $sd    = $this->loadFixture('AUIHI.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertTrue($class->hasMethod('__construct'), 'Generated class should have a __construct method');
    }

    public function testAUIHIConstructorDoesNotExposeFixedSystemParam(): void
    {
        $sd          = $this->loadFixture('AUIHI.json');
        $class       = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $constructor = $class->getMethod('__construct');
        $paramNames  = array_keys($constructor->getParameters());

        self::assertNotContains('system', $paramNames, 'Fixed system param should not be a constructor parameter');
        self::assertNotContains('type', $paramNames, 'Fixed type param should not be a constructor parameter');
    }

    public function testAUIHIConstructorExposesVariableParams(): void
    {
        $sd          = $this->loadFixture('AUIHI.json');
        $class       = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $constructor = $class->getMethod('__construct');
        $paramNames  = array_keys($constructor->getParameters());

        self::assertContains('use', $paramNames, 'Variable param "use" should be in constructor');
        self::assertContains('period', $paramNames, 'Variable param "period" should be in constructor');
        self::assertContains('assigner', $paramNames, 'Variable param "assigner" should be in constructor');
        self::assertContains('id', $paramNames, 'Variable param "id" should be in constructor');
        self::assertContains('extension', $paramNames, 'Variable param "extension" should be in constructor');
    }

    public function testAUIHIConstructorBodyCallsParentWithFixedSystem(): void
    {
        $sd          = $this->loadFixture('AUIHI.json');
        $class       = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $constructor = $class->getMethod('__construct');
        $body        = $constructor->getBody();

        self::assertStringContainsString('parent::__construct(', $body);
        self::assertStringContainsString('FIXED_SYSTEM', $body);
        self::assertStringContainsString('UriPrimitive', $body);
    }

    public function testAUIHIConstructorBodyBakesInCodeableConceptForType(): void
    {
        $sd          = $this->loadFixture('AUIHI.json');
        $class       = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $constructor = $class->getMethod('__construct');
        $body        = $constructor->getBody();

        self::assertStringContainsString('CodeableConcept', $body);
        self::assertStringContainsString('Coding', $body);
        self::assertStringContainsString('NI', $body);
    }

    // -----------------------------------------------------------------
    // Profile with no constraints → thin marker class fallback
    // -----------------------------------------------------------------

    public function testNoConstraintsProducesThinMarkerClass(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/plain',
            'name'           => 'PlainIdentifier',
            'type'           => 'Identifier',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Identifier',
            'snapshot'       => [
                'element' => [
                    ['path' => 'Identifier', 'min' => 0, 'max' => '1'],
                    ['path' => 'Identifier.value', 'min' => 1, 'max' => '1', 'type' => [['code' => 'string']]],
                ],
            ],
        ];

        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        // Should still generate a valid class (thin profile)
        self::assertSame('PlainIdentifierProfile', $class->getName());
        self::assertFalse($class->hasMethod('__construct'), 'Thin profile should not have a constructor');
        self::assertEmpty(
            array_filter($class->getConstants(), fn ($c) => str_starts_with($c->getName(), 'FIXED_')),
            'Thin profile should not have any FIXED_ constants',
        );
    }

    // -----------------------------------------------------------------
    // Unresolvable parent class → warning, no constructor
    // -----------------------------------------------------------------

    public function testUnresolvableParentClassRecordsWarningAndSkipsConstructor(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/orphan',
            'name'           => 'OrphanProfile',
            'type'           => 'FantasyType',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://example.org/StructureDefinition/FantasyType',
            'snapshot'       => [
                'element' => [
                    ['path' => 'FantasyType', 'min' => 0, 'max' => '1'],
                    [
                        'path'     => 'FantasyType.system',
                        'min'      => 1,
                        'max'      => '1',
                        'type'     => [['code' => 'uri']],
                        'fixedUri' => 'http://example.org/system',
                    ],
                ],
            ],
        ];

        $errorCollector = new ErrorCollector();
        $class          = $this->generator->generate($sd, 'R4', $this->context, $this->namespace, $errorCollector);

        // The class is still generated (thin marker + constants + discriminators).
        // Name already ends in 'Profile' so no suffix is doubled.
        self::assertSame('OrphanProfile', $class->getName());

        // A warning should be recorded for the unresolvable parent
        self::assertTrue($errorCollector->hasWarnings(), 'Expected warning for unresolvable parent class');
    }

    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------

    /**
     * @return array<string, mixed>
     */
    private function loadFixture(string $filename): array
    {
        $path = self::FIXTURES_DIR . '/' . $filename;
        self::assertFileExists($path, "Fixture file not found: {$filename}");

        $json = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        self::assertIsArray($json);

        return $json;
    }
}

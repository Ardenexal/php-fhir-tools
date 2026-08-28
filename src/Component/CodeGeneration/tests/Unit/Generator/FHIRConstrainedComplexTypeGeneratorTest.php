<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
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
    // Unresolvable parent class → hard failure
    // -----------------------------------------------------------------

    /**
     * A parent class that cannot be loaded is now a generation-time failure rather than a warning
     * plus an invented FQCN. The invented name went straight into the `extends` clause, and PHPStan
     * treats a missing parent as a severe error that aborts the consuming project's whole analysis —
     * so the "recoverable" path actually hid every other finding in the generated tree.
     *
     * @return void
     */
    public function testUnresolvableParentClassThrows(): void
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

        $this->expectException(GenerationException::class);
        $this->expectExceptionMessageMatches('/Could not resolve baseDefinition URL/');
        $this->expectExceptionMessageMatches('/FantasyType/');

        $this->generator->generate($sd, 'R4', $this->context, $this->namespace, new ErrorCollector());
    }

    /**
     * A versioned canonical must resolve to the unversioned parent instead of pascal-casing the
     * version into the class name (`Identifier|4.0.1` → `Identifier401`, which does not exist).
     *
     * @return void
     */
    public function testVersionedBaseDefinitionResolvesToUnversionedParent(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/versioned-identifier',
            'name'           => 'VersionedIdentifierProfile',
            'type'           => 'Identifier',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Identifier|4.0.1',
            'snapshot'       => [
                'element' => [
                    ['path' => 'Identifier', 'min' => 0, 'max' => '1'],
                    [
                        'path'     => 'Identifier.system',
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

        self::assertFalse(
            $errorCollector->hasWarnings(),
            'A versioned baseDefinition must resolve, not fall through to the fallback',
        );

        $parent = ltrim((string) $class->getExtends(), '\\');
        self::assertTrue(class_exists($parent), "Emitted parent class does not exist: {$parent}");
        self::assertStringNotContainsString('401', $parent, 'Version suffix leaked into the parent class name');
    }

    /**
     * Fixed values must be constructed with a named `value:` argument. The primitive constructors are
     * `(id, extension, value)`, so the previous positional form set `id` and left `value` null —
     * every profile-built Identifier serialized as `<system id="…"/>` with no system at all.
     *
     * @return void
     */
    public function testFixedPrimitiveValuesUseNamedValueArgument(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/StructureDefinition/named-arg-identifier',
            'name'           => 'NamedArgIdentifierProfile',
            'type'           => 'Identifier',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Identifier',
            'snapshot'       => [
                'element' => [
                    ['path' => 'Identifier', 'min' => 0, 'max' => '1'],
                    [
                        'path'     => 'Identifier.system',
                        'min'      => 1,
                        'max'      => '1',
                        'type'     => [['code' => 'uri']],
                        'fixedUri' => 'http://example.org/system',
                    ],
                    [
                        'path'                   => 'Identifier.type',
                        'min'                    => 1,
                        'max'                    => '1',
                        'type'                   => [['code' => 'CodeableConcept']],
                        'patternCodeableConcept' => [
                            'coding' => [[
                                'system' => 'http://terminology.hl7.org/CodeSystem/v2-0203',
                                'code'   => 'NI',
                            ]],
                        ],
                    ],
                ],
            ],
        ];

        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace, new ErrorCollector());
        $body  = (string) $class->getMethod('__construct')->getBody();

        self::assertStringContainsString('new UriPrimitive(value: self::FIXED_SYSTEM)', $body);
        self::assertStringContainsString("code: new CodePrimitive(value: 'NI')", $body);
        self::assertStringContainsString(
            "system: new UriPrimitive(value: 'http://terminology.hl7.org/CodeSystem/v2-0203')",
            $body,
        );

        // No primitive may be constructed positionally: `new XPrimitive('…')` or `new XPrimitive(self::…)`
        // would land the scalar in `id`.
        self::assertDoesNotMatchRegularExpression(
            '/new [A-Za-z]+Primitive\\((?!value:)/',
            $body,
            'A primitive is still constructed positionally, which sets id instead of value',
        );
    }

    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------

    /**
     * @return array<string, mixed>
     */
    // -----------------------------------------------------------------
    // Constructor parameter types — PHPStan level 8 compatibility
    // -----------------------------------------------------------------

    /**
     * An array param is forwarded verbatim to a parent that declares it non-nullable, so
     * declaring it `?array` advertises an argument the profile cannot pass on — PHPStan
     * level 8 reports `argument.type` and passing null is a runtime TypeError.
     */
    public function testIterableParamMirrorsNonNullableParent(): void
    {
        $sd    = $this->loadFixture('AUIHI.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $param = $class->getMethod('__construct')->getParameter('extension');

        self::assertSame('array', $param->getType());
        self::assertFalse($param->isNullable(), 'extension must not be nullable: the parent declares array, not ?array');
        self::assertSame([], $param->getDefaultValue());
        self::assertStringNotContainsString('?array $extension', (string) $class);
    }

    /**
     * PHP has no native generics, so level 8 needs the element type from a docblock
     * (`missingType.iterableValue`). The shape must match the parent's `@var` exactly —
     * `list<Extension>` against an `array<Extension>` parent trades one error for another.
     */
    public function testIterableParamCarriesElementTypeDocblock(): void
    {
        $sd       = $this->loadFixture('AUIHI.json');
        $class    = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $comment  = (string) $class->getMethod('__construct')->getComment();

        self::assertStringContainsString('@param array<Extension> $extension', $comment);
    }

    /**
     * The docblock is resolved in the generated file's namespace, so the element type has to
     * be imported or PHPStan swaps `missingType.iterableValue` for "class not found".
     */
    public function testIterableElementTypeIsImported(): void
    {
        $sd = $this->loadFixture('AUIHI.json');
        $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertContains(
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType\\Extension',
            array_values($this->namespace->getUses()),
        );
    }

    /**
     * Generalises the extension case: no exposed param may be nullable unless the parent
     * param it forwards to is, since the value goes straight through parent::__construct().
     */
    public function testNoParamWidensANonNullableParent(): void
    {
        $sd          = $this->loadFixture('AUIHI.json');
        $class       = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $parentCtor  = (new \ReflectionClass(Identifier::class))->getConstructor();
        self::assertNotNull($parentCtor);

        $parentAllowsNull = [];

        foreach ($parentCtor->getParameters() as $parentParam) {
            $type                                        = $parentParam->getType();
            $parentAllowsNull[$parentParam->getName()]   = $type === null || $type->allowsNull();
        }

        foreach ($class->getMethod('__construct')->getParameters() as $name => $param) {
            self::assertArrayHasKey($name, $parentAllowsNull, "Unexpected param {$name}");

            if ($parentAllowsNull[$name]) {
                continue;
            }

            self::assertFalse(
                $param->isNullable(),
                "Param {$name} is nullable but forwards to a non-nullable parent parameter",
            );
        }
    }

    /**
     * Guards the positional contract: `new AUIHIProfile('…')` must still set `value`.
     */
    public function testRequiredValueParamStaysFirst(): void
    {
        $sd         = $this->loadFixture('AUIHI.json');
        $class      = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $paramNames = array_keys($class->getMethod('__construct')->getParameters());

        self::assertSame('value', $paramNames[0] ?? null);
    }

    private function loadFixture(string $filename): array
    {
        $path = self::FIXTURES_DIR . '/' . $filename;
        self::assertFileExists($path, "Fixture file not found: {$filename}");

        $json = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
        self::assertIsArray($json);

        return $json;
    }
}

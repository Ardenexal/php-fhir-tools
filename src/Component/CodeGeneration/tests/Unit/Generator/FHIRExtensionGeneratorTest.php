<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\CodeGeneration\tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRExtensionGenerator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for FHIRExtensionGenerator.
 *
 * Verifies that named FHIR extension StructureDefinitions are correctly converted
 * into typed PHP extension classes.
 *
 * @see FHIRExtensionGenerator
 */
class FHIRExtensionGeneratorTest extends TestCase
{
    private const string FIXTURES_DIR = __DIR__ . '/../../Fixtures/StructureDefinitions';

    private FHIRExtensionGenerator $generator;

    private BuilderContext $context;

    private PhpNamespace $namespace;

    protected function setUp(): void
    {
        $this->generator = new FHIRExtensionGenerator();

        $this->context = new BuilderContext();
        $this->context->addElementNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource'));
        $this->context->addDatatypeNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType'));
        $this->context->addPrimitiveNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Primitive'));
        $this->context->addEnumNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum'));

        // Pre-register Address so the generator can resolve it from context
        $addressClass = new ClassType('Address', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType'));
        $this->context->addType(
            'http://hl7.org/fhir/StructureDefinition/Address',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType',
            $addressClass,
        );

        // Pre-register Coding
        $codingClass = new ClassType('Coding', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType'));
        $this->context->addType(
            'http://hl7.org/fhir/StructureDefinition/Coding',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType',
            $codingClass,
        );

        // Pre-register CodeableConcept
        $ccClass = new ClassType('CodeableConcept', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType'));
        $this->context->addType(
            'http://hl7.org/fhir/StructureDefinition/CodeableConcept',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType',
            $ccClass,
        );

        // Pre-register Period
        $periodClass = new ClassType('Period', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType'));
        $this->context->addType(
            'http://hl7.org/fhir/StructureDefinition/Period',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType',
            $periodClass,
        );

        $this->namespace = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\TestIg\\Extension');
    }

    // -----------------------------------------------------------------
    // Simple extension (patient-birthPlace: single value type = Address)
    // -----------------------------------------------------------------

    public function testSimpleExtensionGeneratesCorrectClassName(): void
    {
        $sd    = $this->loadFixture('SimpleExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertSame('PatientBirthPlaceExtension', $class->getName());
    }

    public function testSimpleExtensionExtendsBaseExtension(): void
    {
        $sd    = $this->loadFixture('SimpleExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $extends = $class->getExtends();
        self::assertNotNull($extends);
        self::assertStringContainsString('Extension', $extends);
        self::assertStringNotContainsString('PatientBirthPlace', $extends);
    }

    public function testSimpleExtensionHasFHIRExtensionDefinitionAttribute(): void
    {
        $sd    = $this->loadFixture('SimpleExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $attributes = $class->getAttributes();
        self::assertNotEmpty($attributes);

        $found = false;
        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'FHIRExtensionDefinition')) {
                $found = true;
                $args  = $attribute->getArguments();
                self::assertSame(
                    'http://hl7.org/fhir/StructureDefinition/patient-birthPlace',
                    $args['url'],
                );
                self::assertSame('R4', $args['fhirVersion']);
            }
        }

        self::assertTrue($found, 'FHIRExtensionDefinition attribute not found on generated class');
    }

    public function testSimpleExtensionHasTypedValueProperty(): void
    {
        $sd    = $this->loadFixture('SimpleExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constructor = $class->getMethod('__construct');
        self::assertNotNull($constructor);

        $params = $constructor->getParameters();
        // Should have valueAddress, id, extension parameters
        self::assertArrayHasKey('valueAddress', $params);

        $valueParam = $params['valueAddress'];
        self::assertTrue($valueParam->isNullable());
        self::assertStringContainsString('Address', (string) $valueParam->getType());
    }

    public function testSimpleExtensionConstructorBodyBakesInUrl(): void
    {
        $sd    = $this->loadFixture('SimpleExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $body = $class->getMethod('__construct')->getBody();
        self::assertStringContainsString(
            'http://hl7.org/fhir/StructureDefinition/patient-birthPlace',
            $body,
        );
        self::assertStringContainsString('parent::__construct', $body);
    }

    // -----------------------------------------------------------------
    // Complex extension (us-core-race: sub-extension slices)
    // -----------------------------------------------------------------

    public function testComplexExtensionGeneratesCorrectClassName(): void
    {
        $sd    = $this->loadFixture('ComplexExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertSame('USCoreRaceExtensionExtension', $class->getName());
    }

    public function testComplexExtensionHasSubExtensionProperties(): void
    {
        $sd    = $this->loadFixture('ComplexExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constructor = $class->getMethod('__construct');
        $params      = $constructor->getParameters();

        // Slices: ombCategory (array), detailed (array), text (scalar/required)
        self::assertArrayHasKey('ombCategory', $params);
        self::assertArrayHasKey('detailed', $params);
        self::assertArrayHasKey('text', $params);

        // Array slices should have array type
        self::assertSame('array', (string) $params['ombCategory']->getType());
        self::assertSame('array', (string) $params['detailed']->getType());
    }

    public function testComplexExtensionConstructorBuildsSubExtensions(): void
    {
        $sd    = $this->loadFixture('ComplexExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $body = $class->getMethod('__construct')->getBody();

        // Body should loop over the array slices to build Extension objects
        self::assertStringContainsString('foreach', $body);
        self::assertStringContainsString('ombCategory', $body);
        self::assertStringContainsString('subExtensions', $body);
        self::assertStringContainsString('parent::__construct', $body);
    }

    public function testComplexExtensionHasExtensionDefinitionAttribute(): void
    {
        $sd    = $this->loadFixture('ComplexExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $found = false;
        foreach ($class->getAttributes() as $attribute) {
            if (str_contains($attribute->getName(), 'FHIRExtensionDefinition')) {
                $found = true;
                self::assertSame(
                    'http://hl7.org/fhir/us/core/StructureDefinition/us-core-race',
                    $attribute->getArguments()['url'],
                );
            }
        }

        self::assertTrue($found, 'FHIRExtensionDefinition attribute missing from complex extension class');
    }

    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------

    // -----------------------------------------------------------------
    // Complex extension fromSubExtensions (individual-genderIdentity: CodeableConcept + Period + string)
    // -----------------------------------------------------------------

    public function testComplexExtensionImplementsFHIRComplexExtensionInterface(): void
    {
        $sd    = $this->loadFixture('IndividualGenderIdentityStyleExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $implements = $class->getImplements();
        self::assertNotEmpty($implements, 'Complex extension class should implement at least one interface');

        $found = false;
        foreach ($implements as $iface) {
            if (str_contains($iface, 'FHIRComplexExtensionInterface')) {
                $found = true;
                break;
            }
        }

        self::assertTrue($found, 'Complex extension class should implement FHIRComplexExtensionInterface');
    }

    public function testComplexExtensionHasFromSubExtensionsStaticMethod(): void
    {
        $sd    = $this->loadFixture('IndividualGenderIdentityStyleExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertTrue(
            $class->hasMethod('fromSubExtensions'),
            'Complex extension must have a fromSubExtensions static method',
        );

        $method = $class->getMethod('fromSubExtensions');
        self::assertTrue($method->isStatic(), 'fromSubExtensions must be static');
    }

    public function testFromSubExtensionsHasSubExtensionsAndIdParameters(): void
    {
        $sd     = $this->loadFixture('IndividualGenderIdentityStyleExtension.json');
        $class  = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $method = $class->getMethod('fromSubExtensions');
        $params = $method->getParameters();

        self::assertArrayHasKey('subExtensions', $params, 'fromSubExtensions must have a $subExtensions parameter');
        self::assertArrayHasKey('id', $params, 'fromSubExtensions must have an $id parameter');

        self::assertSame('array', (string) $params['subExtensions']->getType());
        self::assertTrue($params['id']->isNullable(), '$id must be nullable');
    }

    public function testFromSubExtensionsBodyChecksSliceUrls(): void
    {
        $sd     = $this->loadFixture('IndividualGenderIdentityStyleExtension.json');
        $class  = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $body   = $class->getMethod('fromSubExtensions')->getBody();

        self::assertStringContainsString("'value'", $body, 'fromSubExtensions body should check slice URL "value"');
        self::assertStringContainsString("'period'", $body, 'fromSubExtensions body should check slice URL "period"');
        self::assertStringContainsString("'comment'", $body, 'fromSubExtensions body should check slice URL "comment"');
        self::assertStringContainsString('return new static(', $body, 'fromSubExtensions must return new static(...)');
    }

    public function testFromSubExtensionsBodyUsesInstanceofForClassTypes(): void
    {
        $sd    = $this->loadFixture('IndividualGenderIdentityStyleExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $body  = $class->getMethod('fromSubExtensions')->getBody();

        self::assertStringContainsString('instanceof', $body, 'fromSubExtensions must use instanceof for class types');
        self::assertStringContainsString('CodeableConcept', $body);
        self::assertStringContainsString('Period', $body);
    }

    public function testFromSubExtensionsBodyUsesInstanceofForFhirStringPrimitive(): void
    {
        $sd    = $this->loadFixture('IndividualGenderIdentityStyleExtension.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $body  = $class->getMethod('fromSubExtensions')->getBody();

        // FHIR 'string' resolves to the StringPrimitive class, so the body should use instanceof
        self::assertStringContainsString('StringPrimitive', $body, 'fromSubExtensions body should reference StringPrimitive for FHIR string slice');
    }

    public function testSimpleExtensionDoesNotImplementComplexInterface(): void
    {
        $sd         = $this->loadFixture('SimpleExtension.json');
        $class      = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $implements = $class->getImplements();

        foreach ($implements as $iface) {
            self::assertStringNotContainsString(
                'FHIRComplexExtensionInterface',
                $iface,
                'Simple extension must NOT implement FHIRComplexExtensionInterface',
            );
        }
    }

    // -----------------------------------------------------------------
    // Cross-package type resolution: unresolvable URL → warning + valid FQCN
    // -----------------------------------------------------------------

    public function testUnresolvableTypeUrlRecordsWarningInErrorCollector(): void
    {
        // A simple extension whose value[x] type is an external URL not in context
        $sd = [
            'resourceType' => 'StructureDefinition',
            'url'          => 'http://example.org/fhir/StructureDefinition/test-ext',
            'name'         => 'TestExt',
            'type'         => 'Extension',
            'derivation'   => 'constraint',
            'kind'         => 'complex-type',
            'snapshot'     => [
                'element' => [
                    ['path' => 'Extension', 'id' => 'Extension'],
                    [
                        'path' => 'Extension.value[x]',
                        'id'   => 'Extension.value[x]',
                        'max'  => '1',
                        'type' => [['code' => 'http://hl7.org/fhir/StructureDefinition/individual-genderIdentity']],
                    ],
                ],
            ],
        ];

        $errorCollector = new ErrorCollector();
        $this->generator->generate($sd, 'R4', $this->context, $this->namespace, $errorCollector);

        self::assertTrue($errorCollector->hasWarnings(), 'Expected a warning for unresolvable cross-package type URL');

        $warnings = $errorCollector->getWarnings();
        self::assertStringContainsString('individual-genderIdentity', $warnings[0]['message']);
        self::assertStringContainsString('Could not resolve type URL', $warnings[0]['message']);
    }

    public function testUnresolvableTypeUrlFallbackProducesValidPhpIdentifier(): void
    {
        // Extension whose value type is a hyphenated cross-package URL not in context
        $sd = [
            'resourceType' => 'StructureDefinition',
            'url'          => 'http://example.org/fhir/StructureDefinition/test-ext',
            'name'         => 'TestExt',
            'type'         => 'Extension',
            'derivation'   => 'constraint',
            'kind'         => 'complex-type',
            'snapshot'     => [
                'element' => [
                    ['path' => 'Extension', 'id' => 'Extension'],
                    [
                        'path' => 'Extension.value[x]',
                        'id'   => 'Extension.value[x]',
                        'max'  => '1',
                        'type' => [['code' => 'individual-genderIdentity']],
                    ],
                ],
            ],
        ];

        $class       = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);
        $constructor = $class->getMethod('__construct');
        $params      = $constructor->getParameters();

        // The type hint must be a valid PHP identifier (no hyphens)
        $valueParam = array_values(array_filter(
            $params,
            static fn ($p) => str_starts_with($p->getName(), 'value'),
        ))[0] ?? null;

        self::assertNotNull($valueParam, 'Expected a value parameter on the constructor');

        $type = (string) $valueParam->getType();
        self::assertStringNotContainsString('-', $type, 'Fallback FQCN must not contain hyphens');
        self::assertStringContainsString('IndividualGenderIdentity', $type, 'Fallback FQCN should be pascal-cased');
    }

    public function testNoWarningWhenTypeIsResolvedFromContext(): void
    {
        // SimpleExtension uses Address, which is pre-registered in setUp()
        $sd             = $this->loadFixture('SimpleExtension.json');
        $errorCollector = new ErrorCollector();
        $this->generator->generate($sd, 'R4', $this->context, $this->namespace, $errorCollector);

        self::assertFalse($errorCollector->hasWarnings(), 'No warnings expected when type resolves from context');
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

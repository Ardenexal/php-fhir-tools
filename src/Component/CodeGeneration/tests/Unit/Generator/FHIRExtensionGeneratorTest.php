<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\CodeGeneration\tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRExtensionGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRExtensionDefinition;
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

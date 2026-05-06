<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Component\CodeGeneration\tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\ErrorCollector;
use Ardenexal\FHIRTools\Component\CodeGeneration\Generator\FHIRProfileGenerator;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for FHIRProfileGenerator.
 *
 * Verifies that constraint StructureDefinitions (derivation=constraint, kind=resource or complex-type)
 * are correctly converted into typed PHP profile classes with PROFILE_URL constants and FHIRProfile
 * attributes, and that multi-level inheritance chains are resolved correctly.
 *
 * @see FHIRProfileGenerator
 */
class FHIRProfileGeneratorTest extends TestCase
{
    private const string FIXTURES_DIR = __DIR__ . '/../../Fixtures/StructureDefinitions';

    private FHIRProfileGenerator $generator;

    private BuilderContext $context;

    private PhpNamespace $namespace;

    protected function setUp(): void
    {
        $this->generator = new FHIRProfileGenerator();

        $this->context = new BuilderContext();
        $this->context->addElementNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource'));
        $this->context->addDatatypeNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType'));
        $this->context->addPrimitiveNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Primitive'));
        $this->context->addEnumNamespace('R4', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Enum'));

        // Pre-register the base PatientResource so the generator can resolve it
        $patientClass = new ClassType('PatientResource', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource'));
        $this->context->addResource(
            'http://hl7.org/fhir/StructureDefinition/Patient',
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Resource',
            $patientClass,
        );

        $this->namespace = new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuBase\\Profile');
    }

    // -----------------------------------------------------------------
    // AU Base Patient profile (extends core PatientResource)
    // -----------------------------------------------------------------

    public function testAUBaseProfileGeneratesCorrectClassName(): void
    {
        $sd    = $this->loadFixture('AUBasePatient.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        self::assertSame('AUBasePatientProfile', $class->getName());
    }

    public function testAUBaseProfileHasFHIRProfileAttribute(): void
    {
        $sd    = $this->loadFixture('AUBasePatient.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $attributes = $class->getAttributes();
        self::assertNotEmpty($attributes);

        $found = false;
        foreach ($attributes as $attribute) {
            if (str_contains($attribute->getName(), 'FHIRProfile')) {
                $found = true;
                $args  = $attribute->getArguments();
                self::assertSame(
                    'http://hl7.org.au/fhir/StructureDefinition/au-patient',
                    $args['profileUrl'],
                );
                self::assertSame('R4', $args['fhirVersion']);
                self::assertSame('Patient', $args['baseType']);
            }
        }

        self::assertTrue($found, 'FHIRProfile attribute not found on generated AU Base profile class');
    }

    public function testAUBaseProfileExtendsPatientResource(): void
    {
        $sd    = $this->loadFixture('AUBasePatient.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $extends = $class->getExtends();
        self::assertNotNull($extends);
        self::assertStringContainsString('PatientResource', $extends);
        self::assertStringNotContainsString('AUBase', $extends);
    }

    public function testAUBaseProfileHasProfileUrlConstant(): void
    {
        $sd    = $this->loadFixture('AUBasePatient.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, $this->namespace);

        $constants = $class->getConstants();
        self::assertArrayHasKey('PROFILE_URL', $constants);
        self::assertSame(
            'http://hl7.org.au/fhir/StructureDefinition/au-patient',
            $constants['PROFILE_URL']->getValue(),
        );
    }

    // -----------------------------------------------------------------
    // AU Core Patient profile (extends AUBasePatientProfile — multi-level)
    // -----------------------------------------------------------------

    public function testAUCoreProfileGeneratesCorrectClassName(): void
    {
        $this->registerAUBaseProfile();

        $sd    = $this->loadFixture('AUCorePatient.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuCore\\Profile'));

        self::assertSame('AUCorePatientProfile', $class->getName());
    }

    public function testAUCoreProfileExtendsAUBaseProfile(): void
    {
        $this->registerAUBaseProfile();

        $sd    = $this->loadFixture('AUCorePatient.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuCore\\Profile'));

        $extends = $class->getExtends();
        self::assertNotNull($extends);

        // The generated class must extend AUBasePatientProfile (the IG profile),
        // not the base PatientResource — this verifies multi-level inheritance resolution.
        self::assertStringContainsString('AUBasePatientProfile', $extends);
        self::assertStringNotContainsString('PatientResource', $extends);
    }

    public function testAUCoreProfileHasProfileUrlConstant(): void
    {
        $this->registerAUBaseProfile();

        $sd    = $this->loadFixture('AUCorePatient.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuCore\\Profile'));

        $constants = $class->getConstants();
        self::assertArrayHasKey('PROFILE_URL', $constants);
        self::assertSame(
            'http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient',
            $constants['PROFILE_URL']->getValue(),
        );
    }

    public function testAUCoreProfileHasFHIRProfileAttribute(): void
    {
        $this->registerAUBaseProfile();

        $sd    = $this->loadFixture('AUCorePatient.json');
        $class = $this->generator->generate($sd, 'R4', $this->context, new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuCore\\Profile'));

        $found = false;
        foreach ($class->getAttributes() as $attribute) {
            if (str_contains($attribute->getName(), 'FHIRProfile')) {
                $found = true;
                $args  = $attribute->getArguments();
                self::assertSame(
                    'http://hl7.org.au/fhir/core/StructureDefinition/au-core-patient',
                    $args['profileUrl'],
                );
            }
        }

        self::assertTrue($found, 'FHIRProfile attribute missing from AU Core profile class');
    }

    // -----------------------------------------------------------------
    // Cross-package base definition: unresolvable URL → warning
    // -----------------------------------------------------------------

    public function testUnresolvableBaseDefinitionRecordsWarning(): void
    {
        // A profile whose baseDefinition is from an unloaded external package
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/fhir/StructureDefinition/my-profile',
            'name'           => 'MyProfile',
            'type'           => 'Patient',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/uv/extensions/StructureDefinition/ext-patient-nationality',
        ];

        $errorCollector = new ErrorCollector();
        $this->generator->generate($sd, 'R4', $this->context, $this->namespace, $errorCollector);

        self::assertTrue($errorCollector->hasWarnings(), 'Expected a warning for unresolvable baseDefinition URL');

        $warnings = $errorCollector->getWarnings();
        self::assertStringContainsString('Could not resolve baseDefinition URL', $warnings[0]['message']);
        self::assertStringContainsString('ext-patient-nationality', $warnings[0]['message']);
    }

    public function testNoWarningWhenBaseDefinitionResolvesFromContext(): void
    {
        $sd             = $this->loadFixture('AUBasePatient.json');
        $errorCollector = new ErrorCollector();
        $this->generator->generate($sd, 'R4', $this->context, $this->namespace, $errorCollector);

        self::assertFalse($errorCollector->hasWarnings(), 'No warnings expected when baseDefinition resolves from context');
    }

    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------

    /**
     * Register the AU Base Patient profile in the BuilderContext as a type, simulating what
     * FHIRIGGeneratorCommand does after generating the au.base package. This enables AU Core
     * to resolve its baseDefinition to AUBasePatientProfile rather than falling back to PatientResource.
     */
    private function registerAUBaseProfile(): void
    {
        $auBaseClass = new ClassType('AUBasePatientProfile', new PhpNamespace('Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuBase\\Profile'));
        $this->context->addType(
            'http://hl7.org.au/fhir/StructureDefinition/au-patient',
            'Ardenexal\\FHIRTools\\Component\\Models\\IG\\R4\\AuBase\\Profile',
            $auBaseClass,
        );
    }

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

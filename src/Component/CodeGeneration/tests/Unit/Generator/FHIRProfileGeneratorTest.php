<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Tests\Unit\Generator;

use Ardenexal\FHIRTools\Component\CodeGeneration\Context\BuilderContext;
use Ardenexal\FHIRTools\Component\CodeGeneration\Exception\GenerationException;
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
            if ($attribute->getName() === FHIRProfile::class) {
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
            if ($attribute->getName() === FHIRProfile::class) {
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
    // Cross-package base definition: unresolvable URL → hard failure
    // -----------------------------------------------------------------

    /**
     * An unresolvable baseDefinition used to be a warning plus an invented FQCN. The invented name
     * was emitted into the `extends` clause of every affected class, and PHPStan reports a missing
     * parent class as a *severe* error that aborts analysis of the entire consuming project — so one
     * unresolvable definition silently hid every other finding in the generated tree. Failing at
     * generation time keeps the error attached to the definition that caused it.
     */
    public function testUnresolvableBaseDefinitionThrows(): void
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

        $this->expectException(GenerationException::class);
        $this->expectExceptionMessageMatches('/Could not resolve baseDefinition URL/');
        $this->expectExceptionMessageMatches('/ext-patient-nationality/');

        $this->generator->generate($sd, 'R4', $this->context, $this->namespace, new ErrorCollector());
    }

    /**
     * Published IGs carry versioned canonicals (`.../Endpoint|4.0.1`). The context indexes
     * definitions under the bare URL, so the version must be stripped before lookup. Without the
     * strip the lookup missed and the fallback pascal-cased the suffix into the class name, emitting
     * `…\Resource\Endpoint401Resource` — a class that does not exist.
     */
    public function testVersionedBaseDefinitionResolvesToUnversionedClass(): void
    {
        $sd = [
            'resourceType'   => 'StructureDefinition',
            'url'            => 'http://example.org/fhir/StructureDefinition/versioned-parent',
            'name'           => 'VersionedParent',
            'type'           => 'Patient',
            'kind'           => 'resource',
            'derivation'     => 'constraint',
            'baseDefinition' => 'http://hl7.org/fhir/StructureDefinition/Patient|4.0.1',
        ];

        $errorCollector = new ErrorCollector();
        $class          = $this->generator->generate($sd, 'R4', $this->context, $this->namespace, $errorCollector);

        self::assertFalse(
            $errorCollector->hasWarnings(),
            'A versioned baseDefinition must resolve, not fall through to the fallback',
        );

        $parent = (string) $class->getExtends();
        self::assertDoesNotMatchRegularExpression(
            '/\d/',
            (string) preg_replace('/^.*\\\\R4\\\\/', '', $parent),
            "Version suffix leaked into the parent class name: {$parent}",
        );
        self::assertTrue(class_exists($parent), "Emitted parent class does not exist: {$parent}");
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

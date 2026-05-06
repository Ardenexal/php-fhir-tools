<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistryFactory;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Tests for FHIRIGTypeRegistryFactory.
 *
 * Key regression covered: the factory must register discriminators even when the IG output
 * directory's namespace is not registered with the project's Composer autoloader (e.g. when
 * the demo app's App\FHIR\IG namespace is scanned from the root project's autoloader context).
 * The factory handles this by registering a PSR-4 autoloader before scanning.
 */
class FHIRIGTypeRegistryFactoryTest extends TestCase
{
    private string $tempDir = '';

    protected function setUp(): void
    {
        parent::setUp();

        $this->tempDir = sys_get_temp_dir() . \DIRECTORY_SEPARATOR . 'fhir_factory_test_' . uniqid();
        mkdir($this->tempDir, 0777, true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->tempDir !== '' && is_dir($this->tempDir)) {
            $this->removeDirectory($this->tempDir);
        }
    }

    // -----------------------------------------------------------------
    // Autoloader-gap regression
    // -----------------------------------------------------------------

    public function testFactoryRegistersDiscriminatorsFromDirectoryNotInAutoloader(): void
    {
        // Write a fixture class to a temp dir whose namespace is NOT in any Composer autoloader.
        // This reproduces the real-world scenario where demo/src/FHIRIG is scanned from the root
        // project context, which has no App\FHIR\IG mapping in vendor/autoload.php.
        $uniqueId  = uniqid('', true);
        $namespace = 'Acme\\TestIG\\Ns' . preg_replace('/[^a-zA-Z0-9]/', '', $uniqueId);
        $className = 'TestIHIProfile';
        $fqcn      = $namespace . '\\' . $className;

        $this->writeFixtureClass($this->tempDir, $className, $namespace);

        $registry = FHIRIGTypeRegistryFactory::create($this->tempDir, $namespace);

        $discriminators = $registry->getSliceDiscriminators(Identifier::class);
        $forFixture     = array_filter($discriminators, fn ($d) => $d->targetClass === $fqcn);

        self::assertNotEmpty(
            $forFixture,
            'Discriminators must be registered even when the namespace is not in the Composer autoloader.',
        );
    }

    public function testFactoryRegistersAllDiscriminatorAttributesOnOneClass(): void
    {
        $uniqueId  = uniqid('', true);
        $namespace = 'Acme\\TestIG2\\Ns' . preg_replace('/[^a-zA-Z0-9]/', '', $uniqueId);
        $className = 'TestIHIProfile';
        $fqcn      = $namespace . '\\' . $className;

        $this->writeFixtureClass($this->tempDir, $className, $namespace);

        $registry       = FHIRIGTypeRegistryFactory::create($this->tempDir, $namespace);
        $discriminators = $registry->getSliceDiscriminators(Identifier::class);
        $forFixture     = array_values(array_filter($discriminators, fn ($d) => $d->targetClass === $fqcn));

        self::assertCount(2, $forFixture, 'Both #[FHIRSliceDiscriminator] attributes must be registered.');
    }

    public function testValueDiscriminatorDataIsCorrect(): void
    {
        $uniqueId  = uniqid('', true);
        $namespace = 'Acme\\TestIG3\\Ns' . preg_replace('/[^a-zA-Z0-9]/', '', $uniqueId);
        $fqcn      = $namespace . '\\TestIHIProfile';

        $this->writeFixtureClass($this->tempDir, 'TestIHIProfile', $namespace);

        $registry       = FHIRIGTypeRegistryFactory::create($this->tempDir, $namespace);
        $discriminators = $registry->getSliceDiscriminators(Identifier::class);

        $valueDisc = null;
        foreach ($discriminators as $d) {
            if ($d->targetClass === $fqcn && $d->type === 'value') {
                $valueDisc = $d;
                break;
            }
        }

        self::assertNotNull($valueDisc, 'value discriminator must be registered');
        self::assertSame('system', $valueDisc->path);
        self::assertSame('http://ns.electronichealth.net.au/id/hi/ihi/1.0', $valueDisc->value);
    }

    // -----------------------------------------------------------------
    // Base model extensions
    // -----------------------------------------------------------------

    public function testBaseModelExtensionsAreAlwaysRegistered(): void
    {
        $registry = FHIRIGTypeRegistryFactory::create();

        self::assertNotEmpty(
            $registry->getExtensionMappings(),
            'Base model extensions must always be registered even with no IG directory.',
        );
    }

    public function testBaseExtensionUrlPointsToAutoloadableClass(): void
    {
        $url      = 'http://hl7.org/fhir/StructureDefinition/individual-genderIdentity';
        $registry = FHIRIGTypeRegistryFactory::create();
        $mappings = $registry->getExtensionMappings();

        self::assertArrayHasKey(
            $url,
            $mappings,
            'individual-genderIdentity extension must be registered from base models.',
        );

        foreach ($mappings[$url] as $version => $class) {
            self::assertTrue(class_exists($class), "Resolved class {$class} for version {$version} must be autoloadable.");
        }
    }

    // -----------------------------------------------------------------
    // Edge cases
    // -----------------------------------------------------------------

    public function testNonExistentIGDirectoryIsHandledGracefully(): void
    {
        $registry = FHIRIGTypeRegistryFactory::create('/nonexistent/path/that/does/not/exist', 'App\\FHIR\\IG');

        // No crash; base extensions still registered
        self::assertNotEmpty($registry->getExtensionMappings());
        self::assertEmpty($registry->getSliceDiscriminatorMappings());
    }

    public function testEmptyIGArgumentsSkipIGScan(): void
    {
        $registry = FHIRIGTypeRegistryFactory::create('', '');

        self::assertNotEmpty($registry->getExtensionMappings());
    }

    // -----------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------

    /**
     * Write a minimal fixture class with two #[FHIRSliceDiscriminator] attributes
     * at $dir/$className.php with the given namespace.
     *
     * The fixture mirrors the shape of a generated AU IHI profile.
     */
    private function writeFixtureClass(string $dir, string $className, string $namespace): void
    {
        file_put_contents($dir . \DIRECTORY_SEPARATOR . $className . '.php', <<<PHP
        <?php
        declare(strict_types=1);
        namespace {$namespace};
        use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
        use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRSliceDiscriminator;
        use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
        #[FHIRProfile(profileUrl: 'http://hl7.org.au/fhir/StructureDefinition/au-ihi', baseType: 'Identifier', fhirVersion: 'R4')]
        #[FHIRSliceDiscriminator(type: 'value', path: 'system', value: 'http://ns.electronichealth.net.au/id/hi/ihi/1.0')]
        #[FHIRSliceDiscriminator(type: 'pattern', path: 'type', value: ['coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v2-0203', 'code' => 'NI']]])]
        class {$className} extends Identifier {}
        PHP);
    }

    private function removeDirectory(string $dir): void
    {
        foreach (scandir($dir) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . \DIRECTORY_SEPARATOR . $item;
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }

        rmdir($dir);
    }
}

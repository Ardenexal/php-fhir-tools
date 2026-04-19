<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Unit;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler\FHIRIGRegistryCompilerPass;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Fixtures\Profile\AUIHIFixtureProfile;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;

/**
 * Verifies that FHIRIGRegistryCompilerPass registers both IG output directory classes
 * AND base models extension classes into FHIRIGTypeRegistry.
 */
class FHIRIGRegistryCompilerPassTest extends TestCase
{
    private function makeContainer(string $igOutputDir = '', string $igNamespace = ''): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->setParameter('fhir.ig.output_directory', $igOutputDir);
        $container->setParameter('fhir.ig.namespace', $igNamespace);

        $registryDef = new Definition(FHIRIGTypeRegistry::class);
        $registryDef->setArgument('$extensionMappings', []);
        $registryDef->setArgument('$profileMappings', []);
        $registryDef->setArgument('$sliceDiscriminatorMappings', []);
        $container->setDefinition(FHIRIGTypeRegistry::class, $registryDef);

        return $container;
    }

    private function makeContainerWithFixtureProfile(): ContainerBuilder
    {
        $fixtureDir = dirname(__DIR__) . '/Fixtures/Profile';
        $fixtureNs  = 'Ardenexal\\FHIRTools\\Bundle\\FHIRBundle\\Tests\\Fixtures\\Profile';

        return $this->makeContainer($fixtureDir, $fixtureNs);
    }

    public function testBaseExtensionClassesAreRegistered(): void
    {
        // PGenderIdentityExtension (individual-genderIdentity) is a base extension
        // from hl7.fhir.uv.extensions.r4, generated into Models/src/R4/Extension/.
        // It must appear in the registry even when no IG output directory is configured.
        $container = $this->makeContainer();

        (new FHIRIGRegistryCompilerPass())->process($container);

        /** @var array<string, class-string> $extensionMappings */
        $extensionMappings = $container->getDefinition(FHIRIGTypeRegistry::class)
            ->getArgument('$extensionMappings');

        self::assertArrayHasKey(
            'http://hl7.org/fhir/StructureDefinition/individual-genderIdentity',
            $extensionMappings,
            'Base extension individual-genderIdentity must be registered from the Models component.',
        );

        self::assertStringContainsString(
            'PGenderIdentityExtension',
            $extensionMappings['http://hl7.org/fhir/StructureDefinition/individual-genderIdentity'],
        );
    }

    public function testBaseExtensionMappingPointsToCorrectNamespace(): void
    {
        $container = $this->makeContainer();
        (new FHIRIGRegistryCompilerPass())->process($container);

        /** @var array<string, class-string> $extensionMappings */
        $extensionMappings = $container->getDefinition(FHIRIGTypeRegistry::class)
            ->getArgument('$extensionMappings');

        $class = $extensionMappings['http://hl7.org/fhir/StructureDefinition/individual-genderIdentity'] ?? null;

        self::assertNotNull($class);
        self::assertMatchesRegularExpression(
            '/^Ardenexal\\\\FHIRTools\\\\Component\\\\Models\\\\R\d[A-Z]?\\\\Extension\\\\/',
            $class,
        );
        self::assertTrue(class_exists($class), "Resolved class {$class} must be autoloadable.");
    }

    public function testMultipleBaseExtensionsAreRegistered(): void
    {
        $container = $this->makeContainer();
        (new FHIRIGRegistryCompilerPass())->process($container);

        /** @var array<string, class-string> $extensionMappings */
        $extensionMappings = $container->getDefinition(FHIRIGTypeRegistry::class)
            ->getArgument('$extensionMappings');

        // At minimum, R4 base extensions from hl7.fhir.uv.extensions.r4 should all be present.
        self::assertGreaterThan(
            1,
            count($extensionMappings),
            'Multiple base extension URL mappings must be registered.',
        );
    }

    public function testEmptyIGDirectoryIsHandledGracefully(): void
    {
        // When IG output dir doesn't exist yet (e.g. first-time setup),
        // the pass must complete without error and still register base extensions.
        $container = $this->makeContainer('/nonexistent/path/that/does/not/exist', 'App\\FHIR\\IG');
        (new FHIRIGRegistryCompilerPass())->process($container);

        /** @var array<string, class-string> $extensionMappings */
        $extensionMappings = $container->getDefinition(FHIRIGTypeRegistry::class)
            ->getArgument('$extensionMappings');

        self::assertArrayHasKey(
            'http://hl7.org/fhir/StructureDefinition/individual-genderIdentity',
            $extensionMappings,
            'Base extensions must still be registered even when IG output dir is absent.',
        );
    }

    public function testNoRegistryDefinitionIsHandledGracefully(): void
    {
        // When FHIRIGTypeRegistry is not registered (e.g. in a non-FHIR container),
        // the pass must return early without throwing.
        $container = new ContainerBuilder();
        $container->setParameter('fhir.ig.output_directory', '');
        $container->setParameter('fhir.ig.namespace', '');

        $this->expectNotToPerformAssertions();
        (new FHIRIGRegistryCompilerPass())->process($container);
    }

    // -----------------------------------------------------------------
    // Slice discriminator mapping tests
    // -----------------------------------------------------------------

    public function testSliceDiscriminatorMappingsAreRegisteredForProfileWithDiscriminatorAttributes(): void
    {
        $container = $this->makeContainerWithFixtureProfile();
        (new FHIRIGRegistryCompilerPass())->process($container);

        /** @var array<string, list<array{type: string, path: string, value: mixed, targetClass: class-string}>> $sliceMappings */
        $sliceMappings = $container->getDefinition(FHIRIGTypeRegistry::class)
            ->getArgument('$sliceDiscriminatorMappings');

        self::assertNotEmpty($sliceMappings, 'Slice discriminator mappings must be populated for profiles with #[FHIRSliceDiscriminator] attributes.');
    }

    public function testSliceDiscriminatorMappingsAreKeyedByBaseTypeFqcn(): void
    {
        $container = $this->makeContainerWithFixtureProfile();
        (new FHIRIGRegistryCompilerPass())->process($container);

        /** @var array<string, list<array{type: string, path: string, value: mixed, targetClass: class-string}>> $sliceMappings */
        $sliceMappings = $container->getDefinition(FHIRIGTypeRegistry::class)
            ->getArgument('$sliceDiscriminatorMappings');

        // The fixture profile extends Identifier, so discriminators must be keyed by Identifier's FQCN.
        $identifierFqcn = Identifier::class;

        self::assertArrayHasKey(
            $identifierFqcn,
            $sliceMappings,
            "Discriminators must be keyed by the parent class FQCN ({$identifierFqcn}).",
        );
    }

    public function testAllDiscriminatorAttributesOnOneClassAreRegistered(): void
    {
        $container = $this->makeContainerWithFixtureProfile();
        (new FHIRIGRegistryCompilerPass())->process($container);

        /** @var array<string, list<array{type: string, path: string, value: mixed, targetClass: class-string}>> $sliceMappings */
        $sliceMappings = $container->getDefinition(FHIRIGTypeRegistry::class)
            ->getArgument('$sliceDiscriminatorMappings');

        $identifierFqcn  = Identifier::class;
        $discriminators  = $sliceMappings[$identifierFqcn] ?? [];
        $fixtureClass    = AUIHIFixtureProfile::class;

        $forFixture = array_filter($discriminators, fn ($d) => $d['targetClass'] === $fixtureClass);

        // The fixture has two #[FHIRSliceDiscriminator] attributes — both must be registered.
        self::assertCount(2, $forFixture, 'Both #[FHIRSliceDiscriminator] attributes on the fixture class must be registered.');
    }

    public function testDiscriminatorDataIsStoredAsPlainArraysNotObjects(): void
    {
        $container = $this->makeContainerWithFixtureProfile();
        (new FHIRIGRegistryCompilerPass())->process($container);

        /** @var array<string, list<array{type: string, path: string, value: mixed, targetClass: class-string}>> $sliceMappings */
        $sliceMappings = $container->getDefinition(FHIRIGTypeRegistry::class)
            ->getArgument('$sliceDiscriminatorMappings');

        $identifierFqcn = Identifier::class;

        foreach ($sliceMappings[$identifierFqcn] ?? [] as $entry) {
            self::assertIsArray($entry, 'Discriminator entries must be plain arrays (not objects) for Symfony container serialization.');
            self::assertArrayHasKey('type', $entry);
            self::assertArrayHasKey('path', $entry);
            self::assertArrayHasKey('value', $entry);
            self::assertArrayHasKey('targetClass', $entry);
        }
    }

    public function testValueDiscriminatorHasCorrectData(): void
    {
        $container = $this->makeContainerWithFixtureProfile();
        (new FHIRIGRegistryCompilerPass())->process($container);

        /** @var array<string, list<array{type: string, path: string, value: mixed, targetClass: class-string}>> $sliceMappings */
        $sliceMappings = $container->getDefinition(FHIRIGTypeRegistry::class)
            ->getArgument('$sliceDiscriminatorMappings');

        $identifierFqcn = Identifier::class;
        $fixtureClass   = AUIHIFixtureProfile::class;
        $discriminators = $sliceMappings[$identifierFqcn] ?? [];

        $valueDiscriminator = null;
        foreach ($discriminators as $d) {
            if ($d['targetClass'] === $fixtureClass && $d['type'] === 'value') {
                $valueDiscriminator = $d;
                break;
            }
        }

        self::assertNotNull($valueDiscriminator, 'Value discriminator for fixture class must be registered.');
        self::assertSame('system', $valueDiscriminator['path']);
        self::assertSame('http://ns.electronichealth.net.au/id/hi/ihi/1.0', $valueDiscriminator['value']);
        self::assertSame($fixtureClass, $valueDiscriminator['targetClass']);
    }

    public function testPatternDiscriminatorHasCorrectData(): void
    {
        $container = $this->makeContainerWithFixtureProfile();
        (new FHIRIGRegistryCompilerPass())->process($container);

        /** @var array<string, list<array{type: string, path: string, value: mixed, targetClass: class-string}>> $sliceMappings */
        $sliceMappings = $container->getDefinition(FHIRIGTypeRegistry::class)
            ->getArgument('$sliceDiscriminatorMappings');

        $identifierFqcn = Identifier::class;
        $fixtureClass   = AUIHIFixtureProfile::class;
        $discriminators = $sliceMappings[$identifierFqcn] ?? [];

        $patternDiscriminator = null;
        foreach ($discriminators as $d) {
            if ($d['targetClass'] === $fixtureClass && $d['type'] === 'pattern') {
                $patternDiscriminator = $d;
                break;
            }
        }

        self::assertNotNull($patternDiscriminator, 'Pattern discriminator for fixture class must be registered.');
        self::assertSame('type', $patternDiscriminator['path']);
        self::assertIsArray($patternDiscriminator['value']);
        self::assertArrayHasKey('coding', $patternDiscriminator['value']);
    }
}

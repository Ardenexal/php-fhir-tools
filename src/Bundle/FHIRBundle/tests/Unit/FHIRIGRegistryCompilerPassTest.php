<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Unit;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler\FHIRIGRegistryCompilerPass;
use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

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
        $container->setDefinition(FHIRIGTypeRegistry::class, $registryDef);

        return $container;
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
        self::assertStringStartsWith(
            'Ardenexal\\FHIRTools\\Component\\Models\\R4\\Extension\\',
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
}

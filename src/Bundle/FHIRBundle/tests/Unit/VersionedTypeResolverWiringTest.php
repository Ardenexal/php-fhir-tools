<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Unit;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler\FHIRVersionedSerializerPass;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRSerializedTypeResolver;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Proves each versioned serializer stack carries a type resolver that knows its own version.
 *
 * `FHIRSerializedTypeResolver` resolves a `resourceType` to a class by namespace. Given a version it
 * scopes strictly to that version and answers null on a miss; given none it searches R4, then R4B,
 * then R5, and returns the first hit. Every stack used to reference the one shared, version-less
 * service, so `{"resourceType":"Patient"}` came back as the **R4** class through the R4B and R5
 * stacks alike — the resolver was doing exactly what an unversioned resolver is documented to do,
 * and nothing was passing it a version.
 *
 * That was invisible to the suite: no test deserialized an unqualified resource through the
 * container's versioned services, so all 4704 passed either way. These assertions are the reason the
 * defect cannot come back silently.
 */
final class VersionedTypeResolverWiringTest extends TestCase
{
    /**
     * @return iterable<string, array{0: string}>
     */
    public static function versionProvider(): iterable
    {
        yield 'R4'  => ['R4'];
        yield 'R4B' => ['R4B'];
        yield 'R5'  => ['R5'];
    }

    /**
     * Each stack registers its own resolver, and that resolver is told which version it serves.
     */
    #[DataProvider('versionProvider')]
    public function testEachVersionStackHasItsOwnVersionScopedResolver(string $version): void
    {
        $container  = self::compiledContainer();
        $id         = 'fhir.type_resolver.' . strtolower($version);

        self::assertTrue($container->hasDefinition($id), "Service {$id} is not registered.");

        $definition = $container->getDefinition($id);
        self::assertSame(FHIRSerializedTypeResolver::class, $definition->getClass());

        // The version is the seventh constructor argument, after the five mapping arrays and the
        // IG registry. Asserting the value rather than merely its presence is the point: passing
        // null here is precisely the bug.
        self::assertSame($version, $definition->getArgument(6));
    }

    /**
     * The service and the normalizers it drives must agree, or deserialization resolves a target
     * class with one and rejects it with the other.
     */
    #[DataProvider('versionProvider')]
    public function testTheSerializationServiceSharesItsStacksResolver(string $version): void
    {
        $container = self::compiledContainer();
        $v         = strtolower($version);
        $expected  = 'fhir.type_resolver.' . $v;

        $service = $container->getDefinition("fhir.serialization_service.{$v}");

        $resolverArgument = $service->getArgument(4);
        self::assertInstanceOf(Reference::class, $resolverArgument);
        self::assertSame($expected, (string) $resolverArgument);

        // And the resource normalizers on the same stack point at the very same service.
        foreach (["fhir.normalizer.resource.json.{$v}", "fhir.normalizer.resource.xml.{$v}"] as $id) {
            $argument = $container->getDefinition($id)->getArgument(1);
            self::assertInstanceOf(Reference::class, $argument);
            self::assertSame($expected, (string) $argument, "{$id} does not share the stack's resolver.");
        }
    }

    /**
     * No stack falls back to the shared, version-less resolver.
     */
    #[DataProvider('versionProvider')]
    public function testNoStackReferencesTheUnversionedResolver(string $version): void
    {
        $container = self::compiledContainer();
        $v         = strtolower($version);

        $unversioned = FHIRSerializedTypeResolver::class;

        foreach ([
            "fhir.serialization_service.{$v}",
            "fhir.normalizer.resource.json.{$v}",
            "fhir.normalizer.complex_type.json.{$v}",
            "fhir.normalizer.operation_payload.json.{$v}.app",
        ] as $id) {
            foreach ($container->getDefinition($id)->getArguments() as $position => $argument) {
                if ($argument instanceof Reference) {
                    self::assertNotSame(
                        $unversioned,
                        (string) $argument,
                        "{$id} argument {$position} still points at the unversioned resolver.",
                    );
                }
            }
        }
    }

    private static function compiledContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.project_dir', '/tmp/test');
        $container->setParameter('kernel.cache_dir', '/tmp/test/cache');

        $bundle = new FHIRBundle();
        $bundle->build($container);

        $extension = $bundle->getContainerExtension();
        self::assertNotNull($extension);

        $extension->load([[
            'output_directory' => '/tmp/test/output',
            'cache_directory'  => '/tmp/test/cache/fhir',
            'default_version'  => 'R4',
        ]], $container);

        foreach ($container->getCompiler()->getPassConfig()->getPasses() as $pass) {
            if ($pass instanceof FHIRVersionedSerializerPass) {
                $pass->process($container);
            }
        }

        return $container;
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Unit;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler\FHIRVersionedSerializerPass;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRBackboneElementJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRComplexTypeJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRPrimitiveTypeJsonNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Proves the complex-type, primitive and backbone JSON normalizers reach the application serializer.
 *
 * Operation payloads were only half the problem. An application that nests a FHIR model inside one of
 * its own classes — typically a generated resource in `DomainResource.contained` — hands that subtree to
 * the framework serializer, which carries no FHIR normalizers. `ObjectNormalizer` then fails it three
 * ways, and only the first is loud:
 *
 *  - a JSON number for a FHIR `decimal` throws, because `Quantity::$value` is a `numeric-string`;
 *  - every `*Primitive` wrapper is built with `value = null`, so `Coding.system`, `Coding.code` and
 *    `CodeableConcept.text` arrive as objects that have lost their contents;
 *  - choice elements resolve to null, because the variant JSON key (`itemCodeableConcept`) never maps
 *    back to the declared property (`item`) without `#[FhirProperty]` metadata.
 *
 * The last two produce no exception and no validation error, which is what makes the registration below
 * worth asserting rather than assuming.
 */
final class SubResourceNormalizerRegistrationTest extends TestCase
{
    /**
     * The three JSON normalizers are registered for the default version.
     */
    public function testSubResourceNormalizersAreRegisteredForTheDefaultVersion(): void
    {
        $container = self::compiledContainer();

        foreach ([
            'fhir.normalizer.complex_type.json.r4.app' => FHIRComplexTypeJsonNormalizer::class,
            'fhir.normalizer.primitive.json.r4.app'    => FHIRPrimitiveTypeJsonNormalizer::class,
            'fhir.normalizer.backbone.json.r4.app'     => FHIRBackboneElementJsonNormalizer::class,
        ] as $id => $class) {
            self::assertTrue($container->hasDefinition($id), "Service {$id} is not registered.");
            self::assertSame($class, $container->getDefinition($id)->getClass());
        }
    }

    /**
     * Tagged above `ObjectNormalizer`, which sits at -1000 and claims any class.
     *
     * Registered below it these would never be consulted, and two of the three failures they prevent are
     * silent — so the priority is asserted, not merely the tag.
     */
    public function testSubResourceNormalizersAreTaggedAboveObjectNormalizer(): void
    {
        $container = self::compiledContainer();

        foreach (['complex_type', 'primitive', 'backbone'] as $kind) {
            $tags = $container->getDefinition("fhir.normalizer.{$kind}.json.r4.app")->getTag('serializer.normalizer');

            self::assertCount(1, $tags, "The {$kind} normalizer is not tagged serializer.normalizer.");
            self::assertGreaterThan(
                0,
                $tags[0]['priority'] ?? 0,
                'Priority must beat ObjectNormalizer (-1000), or the FHIR internals are silently lost.',
            );
        }
    }

    /**
     * No inner serializer reference, unlike the operation-payload pair.
     *
     * A mixed tree alternates between application classes and FHIR ones, so the nested legs have to
     * resolve back through the *application* chain. Pinning them to the FHIR serializer would strand the
     * application's own classes, which that chain cannot build. `SerializerAwareInterface` wires the
     * correct inner leg automatically, so the argument must stay absent.
     */
    public function testSubResourceNormalizersDoNotPinAnInnerSerializer(): void
    {
        $container = self::compiledContainer();

        foreach (['complex_type' => 6, 'primitive' => 5, 'backbone' => 5] as $kind => $expectedArgumentCount) {
            $arguments = $container->getDefinition("fhir.normalizer.{$kind}.json.r4.app")->getArguments();

            self::assertCount(
                $expectedArgumentCount,
                $arguments,
                "The {$kind} normalizer's argument list no longer matches its constructor.",
            );

            foreach ($arguments as $argument) {
                self::assertNotSame(
                    'fhir.serializer.r4',
                    $argument instanceof Reference ? (string) $argument : null,
                    "The {$kind} normalizer must resolve nested values through the application chain.",
                );
            }
        }
    }

    /**
     * Only the default version is registered.
     *
     * These normalizers match on the presence of `#[FHIRComplexType]` / `#[FHIRPrimitive]` /
     * `#[FHIRBackboneElement]` and never compare `fhirVersion`, so three stacks in one chain would all
     * claim the same class, with tag order picking the winner and the losing versions resolving the wrong
     * base Extension FQCN. The payload normalizers can be registered per version because their payload
     * classes are version-scoped; these are not.
     */
    public function testNonDefaultVersionsAreNotRegistered(): void
    {
        $container = self::compiledContainer();

        foreach (['r4b', 'r5'] as $v) {
            foreach (['complex_type', 'primitive', 'backbone'] as $kind) {
                self::assertFalse(
                    $container->hasDefinition("fhir.normalizer.{$kind}.json.{$v}.app"),
                    "fhir.normalizer.{$kind}.json.{$v}.app must not be registered: these normalizers do not "
                    . 'version-filter, so a second stack would shadow the default one.',
                );
            }
        }
    }

    /**
     * The FHIR-chain instances are left alone.
     *
     * They are wired into `fhir.serializer.{v}` and receive their inner serializer through
     * `SerializerAwareInterface`. Tagging those same instances into the application chain would call
     * `setSerializer()` a second time and overwrite the reference `FHIRSerializationService` depends on —
     * which is why separate `.app` instances exist at all.
     */
    public function testFhirChainInstancesAreNotTaggedIntoTheApplicationSerializer(): void
    {
        $container = self::compiledContainer();

        foreach (['complex_type', 'primitive', 'backbone'] as $kind) {
            self::assertSame(
                [],
                $container->getDefinition("fhir.normalizer.{$kind}.json.r4")->getTag('serializer.normalizer'),
                "The in-chain {$kind} normalizer must not also be tagged for the application serializer.",
            );
        }
    }

    /**
     * The resource normalizer stays out of the application chain.
     *
     * `denormalizeFromJSON()` guards `$resolvedType !== $type && !is_subclass_of($resolvedType, $type)`.
     * An application that subclasses a generated resource inverts that relation — the resolver returns
     * the generated class, the caller asked for the subclass that extends it — so registering this here
     * would make every such class throw.
     */
    public function testResourceNormalizerIsNotRegisteredForTheApplicationSerializer(): void
    {
        $container = self::compiledContainer();

        self::assertFalse(
            $container->hasDefinition('fhir.normalizer.resource.json.r4.app'),
            'The resource normalizer rejects application subclasses of generated resources.',
        );
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

        // Run the compiler passes without a full compile(): the pass under test registers these
        // services, and a full compile would also try to resolve unrelated environment-dependent
        // services this test has no interest in.
        foreach ($container->getCompiler()->getPassConfig()->getPasses() as $pass) {
            if ($pass instanceof FHIRVersionedSerializerPass) {
                $pass->process($container);
            }
        }

        return $container;
    }
}

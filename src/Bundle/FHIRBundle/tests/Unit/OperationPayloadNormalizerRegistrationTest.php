<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Unit;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIROperationPayloadJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIROperationPayloadXmlNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler\FHIRVersionedSerializerPass;

/**
 * Proves the bundle wires operation-payload normalizers into **both** serializers.
 *
 * Generated `…Input`/`…Output` classes reach the wire two different ways and both must work:
 *
 *  1. Through `FHIRSerializationService`, where the FHIR normalizer chain handles them directly.
 *  2. Through the **application's** serializer — which is what API Platform's `input:` option uses:
 *
 * ```php
 * #[Post(
 *     uriTemplate: '/ValueSet/{id}/$validate-code',
 *     input:       ValueSetValidateCodeInput::class,
 *     processor:   ValueSetValidateCodeProcessor::class,
 * )]
 * ```
 *
 * The second path is the fragile one. The application serializer contains no FHIR normalizers, so
 * without a tagged service `ObjectNormalizer` claims the class, finds none of its constructor
 * arguments in a `Parameters` body, and returns an object with every property null — no exception,
 * no validation error. Everything asserted here exists to keep that from silently regressing.
 */
final class OperationPayloadNormalizerRegistrationTest extends TestCase
{
    /**
     * Both serializers get a payload normalizer pair, per version.
     *
     * Two instances rather than one shared service: a single service registered inside
     * `fhir.serializer.{v}` *and* pointing at it for its inner `Parameters` leg would be a circular
     * dependency.
     */
    #[DataProvider('versionProvider')]
    public function testBothNormalizerPairsAreRegisteredPerVersion(string $version): void
    {
        $container = self::compiledContainer();
        $v         = strtolower($version);

        foreach ([
            "fhir.normalizer.operation_payload.json.{$v}"     => FHIROperationPayloadJsonNormalizer::class,
            "fhir.normalizer.operation_payload.xml.{$v}"      => FHIROperationPayloadXmlNormalizer::class,
            "fhir.normalizer.operation_payload.json.{$v}.app" => FHIROperationPayloadJsonNormalizer::class,
            "fhir.normalizer.operation_payload.xml.{$v}.app"  => FHIROperationPayloadXmlNormalizer::class,
        ] as $id => $class) {
            self::assertTrue($container->hasDefinition($id), "Service {$id} is not registered.");
            self::assertSame($class, $container->getDefinition($id)->getClass());
        }
    }

    /**
     * The `.app` instances are tagged for the framework serializer, above `ObjectNormalizer`.
     *
     * `ObjectNormalizer` sits at -1000 and claims any class. A payload normalizer registered below
     * it would never be consulted, and the failure would be a silently empty DTO rather than an
     * error — so the priority is asserted, not just the tag.
     */
    #[DataProvider('versionProvider')]
    public function testApplicationNormalizersAreTaggedAboveObjectNormalizer(string $version): void
    {
        $container = self::compiledContainer();
        $v         = strtolower($version);

        foreach (['json', 'xml'] as $format) {
            $definition = $container->getDefinition("fhir.normalizer.operation_payload.{$format}.{$v}.app");
            $tags       = $definition->getTag('serializer.normalizer');

            self::assertCount(1, $tags, "The {$format} normalizer is not tagged serializer.normalizer.");
            self::assertGreaterThan(
                0,
                $tags[0]['priority'] ?? 0,
                'Priority must beat ObjectNormalizer (-1000), or the payload silently comes back empty.',
            );
        }
    }

    /**
     * The tagged instances delegate their inner `Parameters` leg to the FHIR serializer.
     *
     * This is the argument for a second instance existing at all. In the application chain there are
     * no FHIR normalizers, so denormalizing the inner `Parameters` through the *outer* serializer
     * would fall through to `ObjectNormalizer` — reintroducing the silent failure one level down,
     * where it is even harder to spot.
     */
    #[DataProvider('versionProvider')]
    public function testApplicationNormalizersUseTheFhirSerializerForTheInnerLeg(string $version): void
    {
        $container = self::compiledContainer();
        $v         = strtolower($version);

        foreach (['json', 'xml'] as $format) {
            $arguments = $container
                ->getDefinition("fhir.normalizer.operation_payload.{$format}.{$v}.app")
                ->getArguments();

            $inner = $arguments[6] ?? null;

            self::assertInstanceOf(Reference::class, $inner, 'No inner serializer was injected.');
            self::assertSame("fhir.serializer.{$v}", (string) $inner);
        }
    }

    /**
     * The FHIR-chain instances take no inner serializer — their siblings do that work.
     */
    #[DataProvider('versionProvider')]
    public function testFhirChainNormalizersRelyOnTheirSiblings(string $version): void
    {
        $container = self::compiledContainer();
        $v         = strtolower($version);

        foreach (['json', 'xml'] as $format) {
            $arguments = $container
                ->getDefinition("fhir.normalizer.operation_payload.{$format}.{$v}")
                ->getArguments();

            self::assertNull(
                $arguments[6] ?? null,
                'The in-chain instance must not reference the serializer that contains it.',
            );
            self::assertSame($version, $arguments[4], 'The normalizer is scoped to the wrong version.');
        }
    }

    /**
     * Payload normalizers come first in the FHIR serializer's normalizer list.
     *
     * Order is the mechanism, not a preference: whichever normalizer claims a class first wins.
     */
    #[DataProvider('versionProvider')]
    public function testPayloadNormalizersLeadTheFhirSerializerChain(string $version): void
    {
        $container = self::compiledContainer();
        $v         = strtolower($version);

        /** @var list<Reference> $normalizers */
        $normalizers = $container->getDefinition("fhir.serializer.{$v}")->getArgument(0);
        $ids         = array_map(static fn (Reference $r): string => (string) $r, $normalizers);

        self::assertSame(
            [
                "fhir.normalizer.operation_payload.json.{$v}",
                "fhir.normalizer.operation_payload.xml.{$v}",
            ],
            array_slice($ids, 0, 2),
        );
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function versionProvider(): iterable
    {
        yield 'R4'  => ['R4'];
        yield 'R4B' => ['R4B'];
        yield 'R5'  => ['R5'];
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

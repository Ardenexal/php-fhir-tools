<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Unit;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\Compiler\FHIRVersionedSerializerPass;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Fixtures\Serializer\CatchAllNormalizer;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Ratio;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRComplexTypeJsonNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\Serializer\DependencyInjection\SerializerPass;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Proves the FHIR normalizers end up inside the application's `serializer` service.
 *
 * Its sibling tests — OperationPayloadNormalizerRegistrationTest and
 * SubResourceNormalizerRegistrationTest — assert that each `…app` *definition* carries the
 * `serializer.normalizer` tag at a priority above `ObjectNormalizer`. That is necessary but not
 * sufficient, and the gap is the whole reason this file exists: Symfony's `SerializerPass` collects
 * that tag at `TYPE_BEFORE_OPTIMIZATION` priority 0, FrameworkBundle is built before the application's
 * own bundles, and a tag added by a pass that runs *later* is simply never seen. Every one of those
 * tag assertions passed while the runtime `serializer` held no FHIR normalizer at all.
 *
 * So these tests assert on the contents of the compiled `serializer` definition instead of on tags,
 * and they build the container the way an application does: FrameworkBundle's half first (a `serializer`
 * service, a catch-all normalizer, an encoder, and `SerializerPass`), then `FHIRBundle::build()` and the
 * extension. Nothing here filters or reorders the pass list — the ordering under test has to be the
 * container's own.
 *
 * @see FHIRVersionedSerializerPass
 */
final class ApplicationSerializerContentsTest extends TestCase
{
    /**
     * Service IDs of every normalizer the bundle tags for the application serializer.
     *
     * The operation-payload pair backs API Platform `input:` / `output:`; the other three fix FHIR
     * models nested inside an application's own classes.
     */
    private const array APPLICATION_NORMALIZER_IDS = [
        'fhir.normalizer.operation_payload.json.r4.app',
        'fhir.normalizer.operation_payload.xml.r4.app',
        'fhir.normalizer.complex_type.json.r4.app',
        'fhir.normalizer.primitive.json.r4.app',
        'fhir.normalizer.backbone.json.r4.app',
    ];

    /**
     * The tagged normalizers are actually in the `serializer` service, not merely tagged.
     */
    public function testTaggedFhirNormalizersReachTheApplicationSerializer(): void
    {
        $normalizerIds = self::applicationSerializerNormalizerIds();

        foreach (self::APPLICATION_NORMALIZER_IDS as $id) {
            self::assertContains(
                $id,
                $normalizerIds,
                "{$id} is tagged serializer.normalizer but never reaches the serializer service. "
                . 'FHIRVersionedSerializerPass must run before Symfony\'s SerializerPass, which collects '
                . 'that tag at TYPE_BEFORE_OPTIMIZATION priority 0.',
            );
        }
    }

    /**
     * They also land above the catch-all that would otherwise claim these classes.
     *
     * Membership alone would not prove much: `ObjectNormalizer` sits at priority -1000 and accepts
     * anything, and two of the three ways it damages a FHIR model are silent — `*Primitive` wrappers
     * built with `value = null`, and choice elements discarded because the variant JSON key never maps
     * back to the declared property. Only a JSON number for a FHIR `decimal` throws.
     */
    public function testFhirNormalizersOutrankTheCatchAllNormalizer(): void
    {
        $normalizerIds = self::applicationSerializerNormalizerIds();
        $catchAll      = array_search('serializer.normalizer.object', $normalizerIds, true);

        self::assertIsInt($catchAll, 'The catch-all normalizer is missing from the serializer service.');

        foreach (self::APPLICATION_NORMALIZER_IDS as $id) {
            $position = array_search($id, $normalizerIds, true);

            self::assertIsInt($position, "{$id} never reaches the serializer service.");
            self::assertLessThan(
                $catchAll,
                $position,
                "{$id} is ordered below the catch-all normalizer, which claims every class it is offered.",
            );
        }
    }

    /**
     * The FHIR-scoped stack the same pass builds still works.
     *
     * `fhir.serializer.{version}` and `fhir.serialization_service.{version}` come out of
     * FHIRVersionedSerializerPass too, so anything that changes when that pass runs has to leave them
     * intact. A bare `Ratio` covers the one loud failure mode: `Quantity::$value` and its relatives are
     * typed `numeric-string`, while FHIR `decimal` arrives as a JSON number.
     */
    public function testTheVersionedSerializationServiceStillDeserializesADecimalGivenAsAJsonNumber(): void
    {
        $container = self::applicationContainer();
        $container->compile();

        $service = $container->get('fhir.serialization_service.r4');
        self::assertInstanceOf(FHIRSerializationService::class, $service);

        $ratio = $service->deserializeFromJson(
            '{"numerator":{"value":100},"denominator":{"value":1}}',
            Ratio::class,
        );

        self::assertInstanceOf(Ratio::class, $ratio);
        self::assertSame('100', $ratio->numerator?->value);
        self::assertSame('1', $ratio->denominator?->value);
    }

    /**
     * The pass wins over an application's own definition of the same service ID.
     *
     * Applications that hit this bug worked around it by declaring these normalizers in
     * `services.yaml` — tags in configuration are collected during extension loading, before any pass
     * runs, so they do reach the serializer. Those workarounds deliberately reuse the bundle's service
     * IDs so the fix would displace them rather than double them up, and this asserts that it does:
     * exactly one entry per ID, holding the pass's definition.
     *
     * Worth pinning because the two definitions are not equivalent. A workaround written against the
     * shared, unversioned `FHIRTypeResolverInterface` resolves R4-first across every installed version,
     * while the pass hands each stack its own `fhir.type_resolver.{version}`. Had the configured
     * definition survived instead, nothing would fail — the wrong version's classes would simply be
     * resolved, silently.
     */
    public function testThePassReplacesAnApplicationsOwnDefinitionOfTheSameId(): void
    {
        $id        = 'fhir.normalizer.complex_type.json.r4.app';
        $container = self::applicationContainer();

        // Stands in for the services.yaml workaround: same ID, same tag, deliberately not the same
        // class, so whichever definition survives is unambiguous.
        $container->register($id, CatchAllNormalizer::class)
            ->addTag('serializer.normalizer', ['priority' => 100]);

        self::processBeforeOptimizationPasses($container);

        $definition = $container->getDefinition($id);
        self::assertSame(
            FHIRComplexTypeJsonNormalizer::class,
            $definition->getClass(),
            'The application-declared definition outlived the pass, so its wiring is what the serializer got.',
        );

        $typeResolver = $definition->getArgument(1);
        self::assertInstanceOf(Reference::class, $typeResolver);
        self::assertSame(
            'fhir.type_resolver.r4',
            (string) $typeResolver,
            'The surviving definition must carry the version-pinned type resolver, not an unversioned one.',
        );

        self::assertSame(
            [$id],
            array_values(array_filter(
                self::serializerNormalizerIds($container),
                static fn (string $normalizerId): bool => $normalizerId === $id,
            )),
            "{$id} reaches the serializer more than once — the pass added to the application's definition "
            . 'instead of replacing it.',
        );
    }

    /**
     * Service IDs held by the `serializer` definition once the before-optimization passes have run.
     *
     * @return list<string>
     */
    private static function applicationSerializerNormalizerIds(): array
    {
        $container = self::applicationContainer();
        self::processBeforeOptimizationPasses($container);

        return self::serializerNormalizerIds($container);
    }

    /**
     * Runs the container's before-optimization passes, and only those.
     *
     * Stopping there is deliberate. A full `compile()` inlines these private normalizers into the
     * `serializer` definition, replacing the `Reference` objects with anonymous `Definition`s and taking
     * the service IDs — the thing worth asserting on — with them.
     */
    private static function processBeforeOptimizationPasses(ContainerBuilder $container): void
    {
        // The real pass list, in the real order. Filtering it here would mean testing an ordering this
        // test invented rather than the one the container produces.
        foreach ($container->getCompiler()->getPassConfig()->getBeforeOptimizationPasses() as $pass) {
            $pass->process($container);
        }
    }

    /**
     * Reads the service IDs out of the `serializer` definition's normalizer argument.
     *
     * @return list<string>
     */
    private static function serializerNormalizerIds(ContainerBuilder $container): array
    {
        $normalizers = $container->getDefinition('serializer')->getArgument(0);
        self::assertIsArray($normalizers);

        $ids = [];

        foreach ($normalizers as $normalizer) {
            if ($normalizer instanceof Reference) {
                $ids[] = (string) $normalizer;
            }
        }

        return $ids;
    }

    /**
     * A container assembled the way a Symfony application assembles one.
     *
     * FrameworkBundle's contribution comes first, because bundle order is what creates the bug: it
     * registers `SerializerPass` at `TYPE_BEFORE_OPTIMIZATION` priority 0 before any application bundle
     * gets to add a pass of its own.
     */
    private static function applicationContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.project_dir', '/tmp/test');
        $container->setParameter('kernel.cache_dir', '/tmp/test/cache');
        // SerializerPass reads this unconditionally; with it true and a data collector present it would
        // also wrap every normalizer in a TraceableNormalizer definition, losing the service IDs.
        $container->setParameter('kernel.debug', false);

        // --- FrameworkBundle's half ---

        $container->register('serializer', Serializer::class)
            ->setArguments([[], []])
            ->setPublic(true);

        $container->register('serializer.normalizer.object', CatchAllNormalizer::class)
            ->addTag('serializer.normalizer', ['priority' => -1000]);

        // The two nulls are the encoder's own optional dependencies. `SerializerPass` binds
        // `array $defaultContext` into every tagged service, and the container rejects a definition that
        // sets a later constructor argument while leaving earlier ones undefined.
        $container->register('serializer.encoder.json', JsonEncoder::class)
            ->setArguments([null, null])
            ->addTag('serializer.encoder');

        // Services the bundle autowires that FrameworkBundle would otherwise provide.
        $container->register(HttpClientInterface::class, MockHttpClient::class);
        $container->register(Filesystem::class, Filesystem::class);
        $container->register(ValidatorInterface::class)
            ->setFactory([Validation::class, 'createValidator']);

        $container->addCompilerPass(new SerializerPass());

        // --- FHIRBundle ---

        $bundle = new FHIRBundle();
        $bundle->build($container);

        $bundle->getContainerExtension()->load([[
            'output_directory' => '/tmp/test/output',
            'cache_directory'  => '/tmp/test/cache/fhir',
            'default_version'  => 'R4',
            // Both default to `cache.app`, which only FrameworkBundle provides. Neither pool has any
            // bearing on normalizer registration.
            'serialization' => ['metadata_cache_pool' => null],
            'validation'    => ['terminology_cache_pool' => null],
        ]], $container);

        return $container;
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Tests\Unit;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\DependencyInjection\FHIRExtension;
use Ardenexal\FHIRTools\Bundle\FHIRBundle\FHIRBundle;
use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\FhirVersion;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationService;
use Ardenexal\FHIRTools\Component\Validation\FHIRValidationViolation;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileAttributeReader;
use Ardenexal\FHIRTools\Component\Validation\Profile\RuntimeProfileResolver;
use Ardenexal\FHIRTools\Component\Validation\Validator\FHIRSliceConstraintValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\ContainerConstraintValidatorFactory;
use Symfony\Component\Validator\DependencyInjection\AddConstraintValidatorsPass;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * `fhir.validation.runtime_profiles` gives the bundle's validation service the profiles it names.
 *
 * The container is built the way an application builds it: the validator takes its constraint
 * validators from the services the bundle tags, so a finding here comes from the bundle's own wiring
 * and not from a hand-built factory.
 */
#[CoversClass(FHIRExtension::class)]
final class RuntimeProfileConfigurationTest extends TestCase
{
    private const string FIXTURES = __DIR__ . '/../Fixtures/RuntimeProfile';

    public function testAProfileFromAConfiguredPackageDirectoryIsEnforced(): void
    {
        $service = self::validationService(['package_dirs' => [self::FIXTURES . '/package']]);

        self::assertSame(
            [['error', '', 'The observation is final']],
            self::findings($service, 'http://example.org/fhir/StructureDefinition/final-observation'),
        );
    }

    public function testAProfileFromAConfiguredFileIsEnforced(): void
    {
        $service = self::validationService([
            'package_dirs' => [self::FIXTURES . '/package'],
            'files'        => [self::FIXTURES . '/subject-observation.json'],
        ]);

        self::assertSame(
            [['error', '', 'The observation has a subject']],
            self::findings($service, 'http://example.org/fhir/StructureDefinition/subject-observation'),
        );
    }

    public function testAConfiguredFileOverridesAPackageDefinitionWithTheSameUrl(): void
    {
        $service = self::validationService([
            'package_dirs' => [self::FIXTURES . '/package'],
            'files'        => [self::FIXTURES . '/final-observation-override.json'],
        ]);

        self::assertSame(
            [['error', '', 'The local definition applies']],
            self::findings($service, 'http://example.org/fhir/StructureDefinition/final-observation'),
        );
    }

    /**
     * A resource checked against a runtime profile has no slice attributes of its own, so the slice
     * validator reads them through the runtime attribute reader.
     */
    public function testTheSliceValidatorIsGivenTheRuntimeAttributeReader(): void
    {
        $container = self::container(['files' => [self::FIXTURES . '/subject-observation.json']]);

        $attributes = $container->getDefinition(FHIRSliceConstraintValidator::class)->getArgument('$attributes');
        self::assertInstanceOf(Reference::class, $attributes);
        self::assertSame(RuntimeProfileAttributeReader::class, $container->getDefinition((string) $attributes)->getClass());
    }

    public function testNothingIsRegisteredWithoutRuntimeProfiles(): void
    {
        $container = self::container([]);

        self::assertFalse($container->hasDefinition(RuntimeProfileResolver::class));
        self::assertArrayNotHasKey('$runtimeProfiles', $container->getDefinition(FHIRValidationService::class)->getArguments());
    }

    /**
     * Findings in the profile's group for an amended Observation with no subject, as severity, path and message.
     *
     * @return list<array{string, string, string}>
     */
    private static function findings(FHIRValidationService $service, string $profile): array
    {
        $observation = FHIRSerializationService::createDefault(FhirVersion::R4)
            ->deserialize('{"resourceType":"Observation","status":"amended","code":{"text":"x"}}');

        return array_values(array_map(
            static fn (FHIRValidationViolation $v): array => [$v->severity, $v->path, $v->message],
            array_filter(
                $service->validate($observation, [$profile])->violations,
                static fn (FHIRValidationViolation $v): bool => $v->profileGroup === $profile,
            ),
        ));
    }

    /**
     * @param array<string, list<string>> $runtimeProfiles
     */
    private static function validationService(array $runtimeProfiles): FHIRValidationService
    {
        $container = self::container($runtimeProfiles);
        $container->compile();

        $service = $container->get(FHIRValidationService::class);
        self::assertInstanceOf(FHIRValidationService::class, $service);

        return $service;
    }

    /**
     * @param array<string, list<string>> $runtimeProfiles
     */
    private static function container(array $runtimeProfiles): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.project_dir', '/tmp/test');
        $container->setParameter('kernel.cache_dir', '/tmp/test/cache');
        $container->setParameter('kernel.debug', false);

        // --- FrameworkBundle's half: a validator whose constraint validators come from tagged services ---

        $container->register('validator.validator_factory', ContainerConstraintValidatorFactory::class)
            ->setArguments([[]]);
        $container->register('validator.builder', ValidatorBuilder::class)
            ->addMethodCall('enableAttributeMapping', [], true)
            ->addMethodCall('setConstraintValidatorFactory', [new Reference('validator.validator_factory')], true);
        $container->register(ValidatorInterface::class, ValidatorInterface::class)
            ->setFactory([new Reference('validator.builder'), 'getValidator']);
        $container->addCompilerPass(new AddConstraintValidatorsPass());

        $container->register(PropertyAccessorInterface::class, PropertyAccessorInterface::class)
            ->setFactory([PropertyAccess::class, 'createPropertyAccessor']);
        $container->register(HttpClientInterface::class, MockHttpClient::class);
        $container->register(Filesystem::class, Filesystem::class);

        // --- FHIRBundle ---

        $bundle = new FHIRBundle();
        $bundle->build($container);
        $bundle->getContainerExtension()?->load([[
            'output_directory' => '/tmp/test/output',
            'cache_directory'  => '/tmp/test/cache/fhir',
            'default_version'  => 'R4',
            'serialization'    => ['metadata_cache_pool' => null],
            'validation'       => ['terminology_cache_pool' => null, 'runtime_profiles' => $runtimeProfiles],
        ]], $container);

        return $container;
    }
}

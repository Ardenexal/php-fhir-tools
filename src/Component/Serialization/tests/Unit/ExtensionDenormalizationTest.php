<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Serialization\FHIRIGTypeRegistry;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractorInterface;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\AbstractFHIRNormalizer;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Concrete subclass of AbstractFHIRNormalizer that exposes the protected
 * denormalizeExtensionArray() method for unit testing.
 */
class TestableNormalizer extends AbstractFHIRNormalizer
{
    /**
     * @param array<array<string, mixed>|object> $extensionData
     *
     * @return array<array<string, mixed>|object>
     */
    public function exposeDenormalizeExtensionArray(array $extensionData, ?string $format, array $context): array
    {
        return $this->denormalizeExtensionArray($extensionData, $format, $context);
    }

    public function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        return [];
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return false;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        return null;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return false;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [];
    }
}

class ExtensionDenormalizationTest extends TestCase
{
    private FHIRMetadataExtractorInterface $metadataExtractor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->metadataExtractor = $this->createStub(FHIRMetadataExtractorInterface::class);
    }

    public function testWithoutRegistryFallsBackToBaseExtensionClass(): void
    {
        $denormalizer = $this->createMock(DenormalizerInterface::class);
        $denormalizer
            ->expects(self::once())
            ->method('denormalize')
            ->with(
                ['url' => 'http://example.com/some-extension', 'valueString' => 'hello'],
                'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType\\Extension',
                'json',
                [],
            )
            ->willReturn(new \stdClass());

        $normalizer = new TestableNormalizer(
            $this->metadataExtractor,
            null,
            $denormalizer,
        );

        $result = $normalizer->exposeDenormalizeExtensionArray(
            [['url' => 'http://example.com/some-extension', 'valueString' => 'hello']],
            'json',
            [],
        );

        self::assertCount(1, $result);
    }

    public function testWithRegistryUsesTypedExtensionClassForKnownUrl(): void
    {
        $typedClass = \stdClass::class;
        $registry   = new FHIRIGTypeRegistry(
            extensionMappings: ['http://hl7.org/fhir/StructureDefinition/patient-birthPlace' => $typedClass],
        );

        $denormalizer = $this->createMock(DenormalizerInterface::class);
        $denormalizer
            ->expects(self::once())
            ->method('denormalize')
            ->with(
                self::anything(),
                $typedClass,
                'json',
                [],
            )
            ->willReturn(new \stdClass());

        $normalizer = new TestableNormalizer(
            $this->metadataExtractor,
            null,
            $denormalizer,
            $registry,
        );

        $normalizer->exposeDenormalizeExtensionArray(
            [['url' => 'http://hl7.org/fhir/StructureDefinition/patient-birthPlace', 'valueAddress' => []]],
            'json',
            [],
        );
    }

    public function testWithRegistryFallsBackForUnknownUrl(): void
    {
        $registry = new FHIRIGTypeRegistry(
            extensionMappings: ['http://example.com/known-ext' => \stdClass::class],
        );

        $denormalizer = $this->createMock(DenormalizerInterface::class);
        $denormalizer
            ->expects(self::once())
            ->method('denormalize')
            ->with(
                self::anything(),
                'Ardenexal\\FHIRTools\\Component\\Models\\R4\\DataType\\Extension',
                'json',
                [],
            )
            ->willReturn(new \stdClass());

        $normalizer = new TestableNormalizer(
            $this->metadataExtractor,
            null,
            $denormalizer,
            $registry,
        );

        $normalizer->exposeDenormalizeExtensionArray(
            [['url' => 'http://example.com/unknown-ext', 'valueString' => 'x']],
            'json',
            [],
        );
    }

    public function testAlreadyDenormalizedObjectsArePassedThrough(): void
    {
        $registry     = new FHIRIGTypeRegistry();
        $denormalizer = $this->createMock(DenormalizerInterface::class);
        $denormalizer->expects(self::never())->method('denormalize');

        $normalizer = new TestableNormalizer(
            $this->metadataExtractor,
            null,
            $denormalizer,
            $registry,
        );

        $existingObject = new \stdClass();
        $result         = $normalizer->exposeDenormalizeExtensionArray([$existingObject], 'json', []);

        self::assertCount(1, $result);
        self::assertSame($existingObject, $result[0]);
    }

    public function testWithoutDenormalizerReturnRawData(): void
    {
        $normalizer = new TestableNormalizer($this->metadataExtractor);

        $rawData = [['url' => 'http://example.com/ext', 'valueString' => 'test']];
        $result  = $normalizer->exposeDenormalizeExtensionArray($rawData, 'json', []);

        // Fallback: raw data returned as-is when no denormalizer
        self::assertSame($rawData, $result);
    }
}

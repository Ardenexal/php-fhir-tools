<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Tests\Unit;

use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRSerializedTypeResolver;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\Extension\ADXPAdditionalLocatorExtension;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRComplexTypeJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Json\FHIRPrimitiveTypeJsonNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRComplexTypeXmlNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml\FHIRPrimitiveTypeXmlNormalizer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Proves both complex-type normalizers build the RESOLVED type, not the declared one.
 *
 * `denormalize()` narrows the declared type to a subclass in two steps — the type resolver's
 * `resolveComplexType()`, then the IG registry's `resolveSliceClass()` — and every property gate
 * afterwards asks `hasProperty($resolvedType, ...)`. Instantiating the declared base class instead
 * makes those gates pass against a subclass they were not built from, and
 * `FHIRModelAccessor::writeValue()` is a deliberate no-op when the property is absent from the
 * instance. The two combine into silent data loss: an IG profile or slice deserializes to its base
 * class with every subclass-declared property discarded, no exception raised.
 *
 * That was invisible to the suite. Nothing else deserializes a complex type through a resolver that
 * narrows, so all 4901 tests passed with the declared type in place. These assertions are the reason
 * the defect cannot come back silently.
 *
 * The narrowing here is driven through the real {@see FHIRSerializedTypeResolver} rather than a
 * double: `resolveComplexType()` returns `$context['expected_type']` directly, which is the same
 * lever production uses, so a change to the resolver's own narrowing contract fails this test too.
 *
 * @author Ardenexal
 */
#[CoversClass(FHIRComplexTypeJsonNormalizer::class)]
#[CoversClass(FHIRComplexTypeXmlNormalizer::class)]
final class ResolvedComplexTypeInstantiationTest extends TestCase
{
    private const string EXTENSION_URL = 'http://example.org/StructureDefinition/additional-locator';

    private const string LOCATOR_VALUE = 'LOCATOR-42';

    /**
     * `ADXPAdditionalLocatorExtension extends Extension` and declares `valueString`, which the base
     * class does not have. Instantiating the base therefore cannot hold the value at all — the
     * property is absent from the object rather than merely null, which is what makes the loss
     * silent instead of a type error.
     */
    public function testTheSubclassUnderTestDeclaresAPropertyItsBaseDoesNot(): void
    {
        self::assertTrue(is_subclass_of(ADXPAdditionalLocatorExtension::class, Extension::class));
        self::assertTrue(property_exists(ADXPAdditionalLocatorExtension::class, 'valueString'));
        self::assertFalse(property_exists(Extension::class, 'valueString'));
    }

    public function testJsonDenormalizationBuildsTheResolvedSubclass(): void
    {
        $result = $this->denormalizeJson(
            ['url' => self::EXTENSION_URL, 'valueString' => self::LOCATOR_VALUE],
            ADXPAdditionalLocatorExtension::class,
        );

        self::assertInstanceOf(
            ADXPAdditionalLocatorExtension::class,
            $result,
            'The resolved subclass must be instantiated; building the declared base silently drops its properties.',
        );
        self::assertSame(self::LOCATOR_VALUE, $result->valueString?->value);
    }

    public function testXmlDenormalizationBuildsTheResolvedSubclass(): void
    {
        $result = $this->denormalizeXml(
            ['@url' => self::EXTENSION_URL, 'valueString' => ['@value' => self::LOCATOR_VALUE]],
            ADXPAdditionalLocatorExtension::class,
        );

        self::assertInstanceOf(
            ADXPAdditionalLocatorExtension::class,
            $result,
            'The resolved subclass must be instantiated; building the declared base silently drops its properties.',
        );
        self::assertSame(self::LOCATOR_VALUE, $result->valueString?->value);
    }

    /**
     * Control: with nothing to narrow to, the declared type is still what gets built. Without this,
     * a normalizer that ignored the declared type entirely would pass the two tests above.
     */
    public function testAnUnnarrowedTypeStillBuildsTheDeclaredClass(): void
    {
        $json = $this->denormalizeJson(['url' => self::EXTENSION_URL], null);
        self::assertSame(Extension::class, $json::class);

        $xml = $this->denormalizeXml(['@url' => self::EXTENSION_URL], null);
        self::assertSame(Extension::class, $xml::class);
    }

    /**
     * @param array<string, mixed> $data
     * @param class-string|null    $narrowTo Subclass the resolver should narrow to, or null for none
     */
    private function denormalizeJson(array $data, ?string $narrowTo): object
    {
        $metadata  = new FHIRMetadataExtractor();
        $complex   = new FHIRComplexTypeJsonNormalizer($metadata, new FHIRSerializedTypeResolver(fhirVersion: 'R5'), fhirVersion: 'R5');
        $primitive = new FHIRPrimitiveTypeJsonNormalizer($metadata, fhirVersion: 'R5');

        $serializer = new Serializer([$primitive, $complex, new ObjectNormalizer()]);
        $complex->setSerializer($serializer);
        $primitive->setSerializer($serializer);

        $result = $complex->denormalize($data, Extension::class, 'json', $this->narrowingContext($narrowTo));

        self::assertIsObject($result);

        return $result;
    }

    /**
     * @param array<string, mixed> $data
     * @param class-string|null    $narrowTo Subclass the resolver should narrow to, or null for none
     */
    private function denormalizeXml(array $data, ?string $narrowTo): object
    {
        $metadata  = new FHIRMetadataExtractor();
        $complex   = new FHIRComplexTypeXmlNormalizer($metadata, new FHIRSerializedTypeResolver(fhirVersion: 'R5'), fhirVersion: 'R5');
        $primitive = new FHIRPrimitiveTypeXmlNormalizer($metadata, fhirVersion: 'R5');

        $serializer = new Serializer([$primitive, $complex, new ObjectNormalizer()]);
        $complex->setSerializer($serializer);
        $primitive->setSerializer($serializer);

        $result = $complex->denormalize($data, Extension::class, 'xml', $this->narrowingContext($narrowTo));

        self::assertIsObject($result);

        return $result;
    }

    /**
     * @param class-string|null $narrowTo
     *
     * @return array<string, mixed>
     */
    private function narrowingContext(?string $narrowTo): array
    {
        return $narrowTo === null ? [] : ['expected_type' => $narrowTo];
    }
}

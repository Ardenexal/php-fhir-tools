<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRComplexTypeNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Metadata\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Component\Serialization\FHIRTypeResolver;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRHumanName;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRAddress;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRReference;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRExtension;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRPatient;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\Generator;
use Eris\TestTrait;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\HumanName;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRBackboneElementNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRPrimitiveTypeNormalizer;
use Ardenexal\FHIRTools\Component\Serialization\Normalizer\FHIRResourceNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

/**
 * Property-based tests for FHIRComplexTypeNormalizer
 *
 * @author Ardenexal
 */
class FHIRComplexTypeNormalizerTest extends TestCase
{
    use TestTrait;

    private FHIRComplexTypeNormalizer $normalizer;

    private FHIRMetadataExtractor $metadataExtractor;

    private FHIRTypeResolver $typeResolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->metadataExtractor = new FHIRMetadataExtractor();
        $this->typeResolver      = new FHIRTypeResolver();
        $this->normalizer        = new FHIRComplexTypeNormalizer(
            $this->metadataExtractor,
            $this->typeResolver,
        );
    }

    /**
     * **Feature: fhir-serialization, Property 12: Complex type normalizer selection**
     * **Validates: Requirements 3.2**
     *
     * Property: For any FHIR complex type object, the serializer should automatically
     * select and use the dedicated complex type normalizer
     */
    public function testComplexTypeNormalizerSelection(): void
    {
        $this->forAll(
            Generator\oneOf(
                $this->generateHumanName(),
                $this->generateAddress(),
                $this->generateReference(),
                $this->generateExtension(),
            ),
        )->then(function($complexTypeObject) {
            // The normalizer should support normalization of any FHIR complex type
            self::assertTrue(
                $this->normalizer->supportsNormalization($complexTypeObject),
                'Complex type normalizer should support normalization of FHIR complex type objects',
            );

            // The normalizer should be able to normalize the object without errors
            $result = $this->normalizer->normalize($complexTypeObject);
            self::assertIsArray($result, 'Normalization should return an array');

            // The normalizer should support denormalization back to the same type
            $className = get_class($complexTypeObject);
            self::assertTrue(
                $this->normalizer->supportsDenormalization($result, $className),
                'Complex type normalizer should support denormalization of normalized data',
            );
        });
    }

    /**
     * **Feature: fhir-serialization, Property 17: Choice element type suffix**
     * **Validates: Requirements 4.2**
     *
     * Property: For any FHIR choice element (value[x] pattern), the serializer should
     * include the correct type suffix in the serialized output
     */
    public function testChoiceElementTypeSuffix(): void
    {
        $this->forAll(
            $this->generateExtensionWithChoiceElements(),
        )->then(function($extension) {
            $result = $this->normalizer->normalize($extension);

            // Check that choice elements maintain their type suffix
            $hasChoiceElement = false;
            foreach ($result as $key => $value) {
                if (str_starts_with($key, 'value') && $key !== 'value') {
                    $hasChoiceElement = true;
                    // The key should contain the type suffix (e.g., valueString, valueInteger)
                    self::assertMatchesRegularExpression(
                        '/^value[A-Z][a-zA-Z]*$/',
                        $key,
                        'Choice element should have proper type suffix format',
                    );
                }
            }

            // If the extension has choice elements, at least one should be present
            if ($extension->valueString !== null || $extension->valueInteger !== null || $extension->valueBoolean !== null || $extension->valueCodeableConcept !== null) {
                self::assertTrue($hasChoiceElement, 'Choice elements should be preserved with type suffix');
            }
        });
    }

    /**
     * **Feature: fhir-serialization, Property 18: Polymorphic reference handling**
     * **Validates: Requirements 4.3**
     *
     * Property: For any FHIR reference with varying target types, the serializer should
     * correctly handle different reference target types
     */
    public function testPolymorphicReferenceHandling(): void
    {
        $this->forAll(
            $this->generatePolymorphicReference(),
        )->then(function($reference) {
            $result = $this->normalizer->normalize($reference);

            // Reference should be normalized as an array
            self::assertIsArray($result, 'Reference should be normalized as array');

            // Reference should preserve its structure
            if ($reference->reference !== null) {
                self::assertArrayHasKey('reference', $result, 'Reference URL should be preserved');
                self::assertEquals($reference->reference, $result['reference']);
            }

            if ($reference->type !== null) {
                self::assertArrayHasKey('type', $result, 'Reference type should be preserved');
                self::assertEquals($reference->type, $result['type']);
            }

            if ($reference->display !== null) {
                self::assertArrayHasKey('display', $result, 'Reference display should be preserved');
                self::assertEquals($reference->display, $result['display']);
            }

            // Should be able to denormalize back to the same reference
            $denormalized = $this->normalizer->denormalize($result, FHIRReference::class);
            self::assertInstanceOf(FHIRReference::class, $denormalized);
            self::assertEquals($reference->reference, $denormalized->reference);
            self::assertEquals($reference->type, $denormalized->type);
            self::assertEquals($reference->display, $denormalized->display);
        });
    }

    private function generateHumanName(): Generator
    {
        return Generator\bind(
            Generator\oneOf(
                Generator\constant(null),
                Generator\oneOf(Generator\constant('usual'), Generator\constant('official'), Generator\constant('temp')),
            ),
            function($use) {
                return Generator\bind(
                    Generator\oneOf(Generator\constant(null), Generator\string()),
                    function($text) use ($use) {
                        return Generator\bind(
                            Generator\oneOf(Generator\constant(null), Generator\string()),
                            function($family) use ($use, $text) {
                                return Generator\bind(
                                    Generator\oneOf(
                                        Generator\constant(null),
                                        Generator\seq(Generator\string()),
                                    ),
                                    function($given) use ($use, $text, $family) {
                                        return Generator\constant(new FHIRHumanName(
                                            use: $use,
                                            text: $text,
                                            family: $family,
                                            given: $given,
                                        ));
                                    },
                                );
                            },
                        );
                    },
                );
            },
        );
    }

    private function generateAddress(): Generator
    {
        return Generator\bind(
            Generator\oneOf(
                Generator\constant(null),
                Generator\oneOf(Generator\constant('home'), Generator\constant('work'), Generator\constant('temp')),
            ),
            function($use) {
                return Generator\bind(
                    Generator\oneOf(Generator\constant(null), Generator\string()),
                    function($city) use ($use) {
                        return Generator\bind(
                            Generator\oneOf(Generator\constant(null), Generator\string()),
                            function($state) use ($use, $city) {
                                return Generator\constant(new FHIRAddress(
                                    use: $use,
                                    city: $city,
                                    state: $state,
                                ));
                            },
                        );
                    },
                );
            },
        );
    }

    private function generateReference(): Generator
    {
        return Generator\bind(
            Generator\oneOf(Generator\constant(null), Generator\string()),
            function($reference) {
                return Generator\bind(
                    Generator\oneOf(Generator\constant(null), Generator\string()),
                    function($display) use ($reference) {
                        return Generator\constant(new FHIRReference(
                            reference: $reference,
                            display: $display,
                        ));
                    },
                );
            },
        );
    }

    private function generateExtension(): Generator
    {
        return Generator\bind(
            Generator\string(),
            function($url) {
                return Generator\constant(new FHIRExtension(url: $url));
            },
        );
    }

    private function generateExtensionWithChoiceElements(): Generator
    {
        return Generator\bind(
            Generator\string(),
            function($url) {
                return Generator\oneOf(
                    Generator\constant(new FHIRExtension(url: $url, valueString: 'test')),
                    Generator\constant(new FHIRExtension(url: $url, valueInteger: 42)),
                    Generator\constant(new FHIRExtension(url: $url, valueBoolean: true)),
                    Generator\constant(new FHIRExtension(url: $url, valueCodeableConcept: ['coding' => [['code' => 'test']]])),
                );
            },
        );
    }

    private function generatePolymorphicReference(): Generator
    {
        return Generator\oneOf(
            // Patient reference
            Generator\constant(new FHIRReference(
                reference: 'Patient/123',
                type: 'Patient',
                display: 'John Doe',
            )),
            // Practitioner reference
            Generator\constant(new FHIRReference(
                reference: 'Practitioner/456',
                type: 'Practitioner',
                display: 'Dr. Smith',
            )),
            // Organization reference
            Generator\constant(new FHIRReference(
                reference: 'Organization/789',
                type: 'Organization',
                display: 'General Hospital',
            )),
        );
    }

    public function testSupportsNormalizationReturnsFalseForNonObjects(): void
    {
        self::assertFalse($this->normalizer->supportsNormalization('string'));
        self::assertFalse($this->normalizer->supportsNormalization(123));
        self::assertFalse($this->normalizer->supportsNormalization([]));
        self::assertFalse($this->normalizer->supportsNormalization(null));
    }

    public function testSupportsNormalizationReturnsFalseForNonComplexTypes(): void
    {
        $patient = new FHIRPatient();
        self::assertFalse($this->normalizer->supportsNormalization($patient));
    }

    public function testSupportsDenormalizationReturnsFalseForNonArrays(): void
    {
        self::assertFalse($this->normalizer->supportsDenormalization('string', FHIRHumanName::class));
        self::assertFalse($this->normalizer->supportsDenormalization(123, FHIRHumanName::class));
        self::assertFalse($this->normalizer->supportsDenormalization(null, FHIRHumanName::class));
    }

    public function testGetSupportedTypes(): void
    {
        $supportedTypes = $this->normalizer->getSupportedTypes('json');
        self::assertArrayHasKey('object', $supportedTypes);
        self::assertTrue($supportedTypes['object']);
    }

    /**
     * **Regression: _given (array primitive) extension data must be merged into StringPrimitive objects.**
     *
     * In FHIR JSON, `"given": [null, "James"]` paired with
     * `"_given": [{"extension": [...]}, null]` means the first element is a null-value
     * primitive that carries extension data. Both values must become `StringPrimitive`
     * objects and the extension must appear on the first one.
     */
    public function testPrimitiveArrayExtensionsAreMergedDuringDenormalization(): void
    {
        $serializer = $this->buildFullSerializer();

        $data = [
            'use'    => 'maiden',
            'family' => 'Windsor',
            'given'  => [null, 'James'],
            '_given' => [
                [
                    'extension' => [
                        ['url' => 'https://example.org/syllable-count', 'valueString' => 'five'],
                    ],
                ],
                null,
            ],
        ];

        /** @var HumanName $result */
        $result = $serializer->denormalize($data, HumanName::class, 'json');

        self::assertIsArray($result->given);
        self::assertCount(2, $result->given);

        // First entry: null value with extension
        $first = $result->given[0];
        self::assertInstanceOf(StringPrimitive::class, $first);
        self::assertNull($first->value);
        self::assertNotEmpty($first->extension);
        self::assertInstanceOf(Extension::class, $first->extension[0]);
        self::assertSame('https://example.org/syllable-count', $first->extension[0]->url);

        // Second entry: "James" with no extension
        $second = $result->given[1];
        self::assertInstanceOf(StringPrimitive::class, $second);
        self::assertSame('James', $second->value);
        self::assertEmpty($second->extension);
    }

    /**
     * **Regression: _family (non-array primitive) extension data must be merged into the StringPrimitive.**
     */
    public function testNonArrayPrimitiveExtensionIsMergedDuringDenormalization(): void
    {
        $serializer = $this->buildFullSerializer();

        $data = [
            'family'  => 'Windsor',
            '_family' => [
                'extension' => [
                    ['url' => 'https://example.org/humanname-own-name', 'valueString' => 'Windsor'],
                ],
            ],
        ];

        /** @var HumanName $result */
        $result = $serializer->denormalize($data, HumanName::class, 'json');

        $family = $result->family;
        self::assertInstanceOf(StringPrimitive::class, $family);
        self::assertSame('Windsor', $family->value);
        self::assertNotEmpty($family->extension);
        self::assertInstanceOf(Extension::class, $family->extension[0]);
        self::assertSame('https://example.org/humanname-own-name', $family->extension[0]->url);
    }

    /**
     * Build a fully-wired Symfony Serializer with all FHIR normalizers chained.
     */
    private function buildFullSerializer(): Serializer
    {
        $metadataExtractor = new FHIRMetadataExtractor();
        $typeResolver      = new FHIRTypeResolver();

        $normalizers = [
            new FHIRResourceNormalizer($metadataExtractor, $typeResolver),
            new FHIRComplexTypeNormalizer($metadataExtractor, $typeResolver),
            new FHIRPrimitiveTypeNormalizer($metadataExtractor),
            new FHIRBackboneElementNormalizer($metadataExtractor),
        ];

        return new Serializer(
            $normalizers,
            [new JsonEncoder()],
        );
    }
}

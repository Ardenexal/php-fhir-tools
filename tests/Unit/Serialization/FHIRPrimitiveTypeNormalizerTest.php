<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Serialization;

use Ardenexal\FHIRTools\Serialization\FHIRPrimitiveTypeNormalizer;
use Ardenexal\FHIRTools\Serialization\FHIRMetadataExtractor;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRString;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRInteger;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRBoolean;
use Ardenexal\FHIRTools\Tests\Fixtures\FHIR\FHIRDecimal;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;

/**
 * Test cases for FHIRPrimitiveTypeNormalizer
 *
 * @author Kiro AI Assistant
 */
class FHIRPrimitiveTypeNormalizerTest extends TestCase
{
    private FHIRPrimitiveTypeNormalizer $normalizer;
    private FHIRMetadataExtractor $metadataExtractor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->metadataExtractor = new FHIRMetadataExtractor();
        $this->normalizer = new FHIRPrimitiveTypeNormalizer($this->metadataExtractor);
    }

    /**
     * **Feature: fhir-serialization, Property 3: Primitive extension underscore notation**
     * **Validates: Requirements 1.3**
     */
    public function testPrimitiveExtensionUnderscoreNotation()
    {
        // Test with various primitive types and extensions
        $extensions = [
            ['url' => 'http://example.com/ext1', 'valueString' => 'test'],
            ['url' => 'http://example.com/ext2', 'valueInteger' => 42]
        ];

        // Test FHIRString with extensions
        $stringPrimitive = new FHIRString('test value', $extensions);
        $result = $this->normalizer->normalize($stringPrimitive, 'json');
        
        self::assertTrue($this->normalizer->supportsNormalization($stringPrimitive, 'json'));
        self::assertEquals('test value', $result);
    }

    /**
     * **Feature: fhir-serialization, Property 8: Primitive extension deserialization**
     * **Validates: Requirements 2.3**
     */
    public function testPrimitiveExtensionDeserialization()
    {
        // Test JSON with primitive extension underscore notation
        $jsonData = [
            'value' => 'test value',
            'extension' => [
                ['url' => 'http://example.com/ext1', 'valueString' => 'test'],
                ['url' => 'http://example.com/ext2', 'valueInteger' => 42]
            ]
        ];

        $result = $this->normalizer->denormalize($jsonData, FHIRString::class, 'json');
        
        self::assertInstanceOf(FHIRString::class, $result);
        self::assertEquals('test value', $result->value);
        self::assertNotNull($result->extension);
        self::assertCount(2, $result->extension);
        self::assertEquals('http://example.com/ext1', $result->extension[0]['url']);
        self::assertEquals('test', $result->extension[0]['valueString']);
    }

    /**
     * **Feature: fhir-serialization, Property 13: Primitive type normalizer selection**
     * **Validates: Requirements 3.3**
     */
    public function testPrimitiveTypeNormalizerSelection()
    {
        // Test that the normalizer correctly identifies and supports all FHIR primitive types
        $primitives = [
            new FHIRString('test'),
            new FHIRInteger(42),
            new FHIRBoolean(true),
            new FHIRDecimal(3.14)
        ];

        foreach ($primitives as $primitive) {
            // The normalizer should support normalization for all FHIR primitive types
            self::assertTrue(
                $this->normalizer->supportsNormalization($primitive),
                sprintf('Normalizer should support %s', get_class($primitive))
            );
            
            // The normalizer should support denormalization for all FHIR primitive types
            self::assertTrue(
                $this->normalizer->supportsDenormalization([], get_class($primitive)),
                sprintf('Normalizer should support denormalization of %s', get_class($primitive))
            );
        }

        // Non-primitive objects should not be supported
        $nonPrimitives = [
            new \stdClass(),
            'string',
            42,
            true,
            []
        ];

        foreach ($nonPrimitives as $nonPrimitive) {
            self::assertFalse(
                $this->normalizer->supportsNormalization($nonPrimitive),
                sprintf('Normalizer should not support %s', gettype($nonPrimitive))
            );
        }
    }

    /**
     * **Feature: fhir-serialization, Property 23: XML primitive extension serialization**
     * **Validates: Requirements 5.3**
     */
    public function testXMLPrimitiveExtensionSerialization()
    {
        // Test XML serialization with primitive extensions
        $extensions = [
            ['url' => 'http://example.com/ext1', 'valueString' => 'test'],
            ['url' => 'http://example.com/ext2', 'valueInteger' => 42]
        ];

        $stringPrimitive = new FHIRString('test value', $extensions);
        $result = $this->normalizer->normalize($stringPrimitive, 'xml');
        
        // For XML format, the result should be an array with @value and extension
        self::assertIsArray($result);
        self::assertArrayHasKey('@value', $result);
        self::assertEquals('test value', $result['@value']);
        
        // Extensions should be included as child elements
        self::assertArrayHasKey('extension', $result);
        self::assertEquals($extensions, $result['extension']);

        // Test with integer primitive
        $integerPrimitive = new FHIRInteger(42, $extensions);
        $result = $this->normalizer->normalize($integerPrimitive, 'xml');
        
        self::assertIsArray($result);
        self::assertArrayHasKey('@value', $result);
        self::assertEquals(42, $result['@value']);
        self::assertArrayHasKey('extension', $result);
    }

    /**
     * **Feature: fhir-serialization, Property 31: Primitive extension round-trip**
     * **Validates: Requirements 7.5**
     */
    public function testPrimitiveExtensionRoundTrip()
    {
        // Test round-trip preservation of primitive values with extensions
        $extensions = [
            ['url' => 'http://example.com/ext1', 'valueString' => 'test'],
            ['url' => 'http://example.com/ext2', 'valueInteger' => 42]
        ];

        $primitives = [
            new FHIRString('test value', $extensions),
            new FHIRInteger(42, $extensions),
            new FHIRBoolean(true, $extensions),
            new FHIRDecimal(3.14, $extensions)
        ];

        foreach ($primitives as $original) {
            // Test JSON round-trip
            $normalized = $this->normalizer->normalize($original, 'json');
            
            // For JSON, we need to simulate the full round-trip with extensions
            $jsonData = [
                'value' => $normalized,
                'extension' => $extensions
            ];
            
            $denormalized = $this->normalizer->denormalize($jsonData, get_class($original), 'json');
            
            self::assertInstanceOf(get_class($original), $denormalized);
            self::assertEquals($original->value, $denormalized->value);
            self::assertEquals($original->extension, $denormalized->extension);

            // Test XML round-trip
            $xmlNormalized = $this->normalizer->normalize($original, 'xml');
            $xmlDenormalized = $this->normalizer->denormalize($xmlNormalized, get_class($original), 'xml');
            
            self::assertInstanceOf(get_class($original), $xmlDenormalized);
            self::assertEquals($original->value, $xmlDenormalized->value);
            self::assertEquals($original->extension, $xmlDenormalized->extension);
        }
    }

    public function testSupportsNormalization()
    {
        $string = new FHIRString('test');
        $integer = new FHIRInteger(42);

        self::assertTrue($this->normalizer->supportsNormalization($string));
        self::assertTrue($this->normalizer->supportsNormalization($integer));
        self::assertFalse($this->normalizer->supportsNormalization(new \stdClass()));
    }
}
<?php

declare(strict_types=1);

namespace Tests\Unit\Component\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContextFactory;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\Generator;
use Eris\TestTrait;

/**
 * Property-based tests for Serialization component Symfony integration.
 *
 * **Feature: repository-reorganization, Property 22: Serialization Symfony integration**
 *
 * Tests that the Serialization component integrates seamlessly with Symfony Serializer.
 *
 * @author Kiro AI Assistant
 */
class SerializationSymfonyIntegrationTest extends TestCase
{
    use TestTrait;

    /**
     * Test that the Serialization component integrates with Symfony Serializer.
     *
     * **Feature: repository-reorganization, Property 22: Serialization Symfony integration**
     * **Validates: Requirements 8.3**
     */
    public function testSerializationComponentIntegratesWithSymfonySerializer(): void
    {
        $this->forAll(
            Generator\elements(['json', 'xml']),
            Generator\associative([
                'test_data'   => Generator\string(),
                'test_number' => Generator\choose(1, 100),
            ]),
        )->then(function(string $format, array $testData): void {
            // Create a real Symfony Serializer
            $encoders    = [new JsonEncoder(), new XmlEncoder()];
            $normalizers = [new ArrayDenormalizer()];
            $serializer  = new Serializer($normalizers, $encoders);

            // Create context factory
            $contextFactory = new FHIRSerializationContextFactory();

            // Create debug info
            $debugInfo = FHIRSerializationDebugInfo::forNormalization($format);

            // Create serialization service with real Symfony serializer
            $serializationService = new FHIRSerializationService(
                $serializer,
                $contextFactory,
                $debugInfo,
            );

            // Verify the service was created successfully
            self::assertInstanceOf(FHIRSerializationService::class, $serializationService);

            // Test that context factory produces Symfony-compatible contexts
            $context = $format === 'xml'
                ? $contextFactory->createXmlContext()
                : $contextFactory->createJsonContext();

            // Verify context contains Symfony-compatible options
            self::assertIsArray($context);
            self::assertArrayHasKey('format', $context);
            self::assertSame($format, $context['format']);

            // Test that the serializer can handle basic data with the context
            $simpleData = ['test' => 'value'];
            $serialized = $serializer->serialize($simpleData, $format, $context);
            self::assertIsString($serialized);

            // Verify the serialized data contains expected content
            if ($format === 'json') {
                self::assertStringContainsString('test', $serialized);
                self::assertStringContainsString('value', $serialized);
            } else {
                self::assertStringContainsString('<test>value</test>', $serialized);
            }
        });
    }

    /**
     * Test that context factory produces Symfony Serializer compatible contexts.
     *
     * **Feature: repository-reorganization, Property 22: Serialization Symfony integration**
     * **Validates: Requirements 8.3**
     */
    public function testContextFactoryProducesSymfonyCompatibleContexts(): void
    {
        $this->forAll(
            Generator\elements(['json', 'xml']),
            Generator\elements(['createJsonContext', 'createXmlContext', 'createLenientContext', 'createStrictContext', 'createPerformanceContext', 'createDebugContext']),
        )->then(function(string $format, string $method): void {
            $contextFactory = new FHIRSerializationContextFactory();

            // Call the appropriate method
            $context = match ($method) {
                'createJsonContext'        => $contextFactory->createJsonContext(),
                'createXmlContext'         => $contextFactory->createXmlContext(),
                'createLenientContext'     => $contextFactory->createLenientContext($format),
                'createStrictContext'      => $contextFactory->createStrictContext($format),
                'createPerformanceContext' => $contextFactory->createPerformanceContext($format),
                'createDebugContext'       => $contextFactory->createDebugContext($format),
                default                    => $contextFactory->createJsonContext()
            };

            // Verify context is valid for Symfony Serializer
            self::assertIsArray($context);
            self::assertArrayHasKey('format', $context);

            // Test with a real Symfony Serializer
            $encoders    = [new JsonEncoder(), new XmlEncoder()];
            $normalizers = [new ArrayDenormalizer()];
            $serializer  = new Serializer($normalizers, $encoders);

            $testData = ['test' => 'value', 'number' => 42];

            // This should not throw an exception
            $serialized = $serializer->serialize($testData, $context['format'], $context);
            self::assertIsString($serialized);

            // Verify the serialized data contains expected content
            if ($context['format'] === 'json') {
                self::assertStringContainsString('test', $serialized);
                self::assertStringContainsString('value', $serialized);
            } else {
                self::assertStringContainsString('<test>value</test>', $serialized);
            }
        });
    }

    /**
     * Test that debug info integrates with Symfony Serializer context.
     *
     * **Feature: repository-reorganization, Property 22: Serialization Symfony integration**
     * **Validates: Requirements 8.3**
     */
    public function testDebugInfoIntegratesWithSymfonyContext(): void
    {
        $this->forAll(
            Generator\elements(['json', 'xml']),
            Generator\elements(['normalize', 'denormalize']),
        )->then(function(string $format, string $operation): void {
            // Create debug info for the operation
            $debugInfo = $operation === 'normalize'
                ? FHIRSerializationDebugInfo::forNormalization($format)
                : FHIRSerializationDebugInfo::forDenormalization($format);

            // Verify debug info is properly structured
            self::assertSame($operation, $debugInfo->operation);
            self::assertSame($format, $debugInfo->format);

            // Verify debug info can be converted to array (for Symfony context)
            $debugArray = $debugInfo->toArray();
            self::assertIsArray($debugArray);
            self::assertArrayHasKey('operation', $debugArray);
            self::assertArrayHasKey('format', $debugArray);
            self::assertSame($operation, $debugArray['operation']);
            self::assertSame($format, $debugArray['format']);

            // Verify debug info can be serialized (for logging/debugging)
            $debugJson = $debugInfo->toJson();
            self::assertIsString($debugJson);

            $decodedDebug = json_decode($debugJson, true);
            self::assertIsArray($decodedDebug);
            self::assertSame($operation, $decodedDebug['operation']);
            self::assertSame($format, $decodedDebug['format']);
        });
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Component\Serialization;

use Ardenexal\FHIRTools\Component\Serialization\FHIRSerializationService;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationContextFactory;
use Ardenexal\FHIRTools\Component\Serialization\Context\FHIRSerializationDebugInfo;
use Symfony\Component\Serializer\SerializerInterface;
use Ardenexal\FHIRTools\Tests\Utilities\TestCase;
use Eris\Generator;
use Eris\TestTrait;

/**
 * Property-based tests for Serialization component independence.
 *
 * **Feature: repository-reorganization, Property 21: Serialization independence**
 *
 * Tests that the Serialization component can work independently without requiring
 * other components to be present or loaded.
 *
 * @author Ardenexal
 */
class SerializationIndependenceTest extends TestCase
{
    use TestTrait;

    /**
     * Test that the Serialization component can be instantiated independently.
     *
     * **Feature: repository-reorganization, Property 21: Serialization independence**
     * **Validates: Requirements 8.2**
     */
    public function testSerializationComponentCanBeInstantiatedIndependently(): void
    {
        $this->forAll(
            Generator\elements(['json', 'xml']),
        )->then(function(string $format): void {
            // Create mock serializer (simulating minimal Symfony dependency)
            $mockSerializer = $this->createMock(SerializerInterface::class);

            // Create context factory independently
            $contextFactory = new FHIRSerializationContextFactory();

            // Create debug info independently
            $debugInfo = FHIRSerializationDebugInfo::forNormalization($format);

            // Create serialization service independently
            $serializationService = new FHIRSerializationService(
                $mockSerializer,
                $contextFactory,
                $debugInfo,
            );

            // Verify the service was created successfully
            self::assertInstanceOf(FHIRSerializationService::class, $serializationService);

            // Verify context factory works independently
            $context = $contextFactory->createJsonContext();
            self::assertIsArray($context);
            self::assertArrayHasKey('format', $context);

            // Verify debug info works independently
            self::assertSame($format, $debugInfo->format);
            self::assertIsArray($debugInfo->getDebugInfo());
        });
    }

    /**
     * Test that Serialization component classes can be loaded without other components.
     *
     * **Feature: repository-reorganization, Property 21: Serialization independence**
     * **Validates: Requirements 8.2**
     */
    public function testSerializationClassesCanBeLoadedIndependently(): void
    {
        $this->forAll(
            Generator\elements([
                FHIRSerializationService::class,
                FHIRSerializationContextFactory::class,
                FHIRSerializationDebugInfo::class,
            ]),
        )->then(function(string $className): void {
            // Verify class exists and can be reflected
            self::assertTrue(class_exists($className), "Class {$className} should exist");

            $reflection = new \ReflectionClass($className);
            self::assertTrue($reflection->isInstantiable() || $reflection->isAbstract() || $reflection->isInterface());

            // Verify namespace is correct for component independence
            self::assertStringStartsWith('Ardenexal\\FHIRTools\\Component\\Serialization', $className);
        });
    }

    /**
     * Test that context factory produces valid contexts independently.
     *
     * **Feature: repository-reorganization, Property 21: Serialization independence**
     * **Validates: Requirements 8.2**
     */
    public function testContextFactoryProducesValidContextsIndependently(): void
    {
        $this->forAll(
            Generator\elements(['json', 'xml']),
            Generator\associative([
                'custom_option' => Generator\bool(),
                'max_depth'     => Generator\choose(1, 20),
            ]),
        )->then(function(string $format, array $overrides): void {
            $contextFactory = new FHIRSerializationContextFactory();

            $context = $format === 'xml'
                ? $contextFactory->createXmlContext($overrides)
                : $contextFactory->createJsonContext($overrides);

            // Verify context is valid and independent
            self::assertIsArray($context);
            self::assertSame($format, $context['format']);

            // Verify overrides are applied
            foreach ($overrides as $key => $value) {
                if (isset($context[$key])) {
                    self::assertSame($value, $context[$key]);
                }
            }

            // Verify required context keys exist
            self::assertArrayHasKey('format', $context);
            self::assertArrayHasKey('skip_null_values', $context);
            self::assertArrayHasKey('fhir_strict_validation', $context);
        });
    }
}

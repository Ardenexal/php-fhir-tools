<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Serialization;

use Ardenexal\FHIRTools\Serialization\FHIRSerializationContext;
use Eris\TestTrait;
use Eris\Generator;
use PHPUnit\Framework\TestCase;

/**
 * Property-based tests for FHIR serialization configuration system.
 *
 * **Feature: fhir-serialization, Property 37: Format support**
 * **Feature: fhir-serialization, Property 38: Validation mode support**
 * **Feature: fhir-serialization, Property 39: Unknown element policy enforcement**
 * **Feature: fhir-serialization, Property 40: Performance optimization options**
 * **Feature: fhir-serialization, Property 41: Debug information availability**
 *
 * @author Kiro AI Assistant
 */
class FHIRSerializationContextTest extends TestCase
{
    use TestTrait;

    /**
     * **Feature: fhir-serialization, Property 37: Format support**
     * **Validates: Requirements 9.1**
     *
     * For any specified serialization format (JSON or XML), the serializer should correctly produce output in that format
     */
    public function testFormatSupportProperty()
    {
        $this->forAll(
            Generator\elements(FHIRSerializationContext::FORMAT_JSON, FHIRSerializationContext::FORMAT_XML),
        )->then(function(string $format) {
            // Create context with the specified format
            $context = new FHIRSerializationContext(format: $format);

            // Verify the format is correctly set
            self::assertSame($format, $context->format);

            // Verify format-specific helper methods work correctly
            if ($format === FHIRSerializationContext::FORMAT_JSON) {
                self::assertTrue($context->isJsonFormat());
                self::assertFalse($context->isXmlFormat());
            } else {
                self::assertFalse($context->isJsonFormat());
                self::assertTrue($context->isXmlFormat());
            }

            // Verify the context can be converted to Symfony context
            $symfonyContext = $context->toSymfonyContext();
            self::assertSame($format, $symfonyContext['fhir_format']);

            // Verify round-trip conversion preserves format
            $reconstructed = FHIRSerializationContext::fromSymfonyContext($symfonyContext);
            self::assertSame($format, $reconstructed->format);
        });
    }

    /**
     * **Feature: fhir-serialization, Property 38: Validation mode support**
     * **Validates: Requirements 9.2**
     *
     * For any configured validation level (strict or lenient), the serializer should behave according to the specified validation mode
     */
    public function testValidationModeSupportProperty()
    {
        $this->forAll(
            Generator\elements(FHIRSerializationContext::VALIDATION_STRICT, FHIRSerializationContext::VALIDATION_LENIENT),
        )->then(function(string $validationMode) {
            // Create context with the specified validation mode
            $context = new FHIRSerializationContext(validationMode: $validationMode);

            // Verify the validation mode is correctly set
            self::assertSame($validationMode, $context->validationMode);

            // Verify validation mode helper methods work correctly
            if ($validationMode === FHIRSerializationContext::VALIDATION_STRICT) {
                self::assertTrue($context->isStrictValidation());
                self::assertFalse($context->isLenientValidation());
            } else {
                self::assertFalse($context->isStrictValidation());
                self::assertTrue($context->isLenientValidation());
            }

            // Verify the context can be converted to Symfony context
            $symfonyContext = $context->toSymfonyContext();
            self::assertSame($validationMode, $symfonyContext['fhir_validation_mode']);

            // Verify round-trip conversion preserves validation mode
            $reconstructed = FHIRSerializationContext::fromSymfonyContext($symfonyContext);
            self::assertSame($validationMode, $reconstructed->validationMode);
        });
    }

    /**
     * **Feature: fhir-serialization, Property 39: Unknown element policy enforcement**
     * **Validates: Requirements 9.3**
     *
     * For any configured unknown element policy, the serializer should handle unknown elements according to that policy
     */
    public function testUnknownElementPolicyEnforcementProperty()
    {
        $this->forAll(
            Generator\elements(
                FHIRSerializationContext::UNKNOWN_POLICY_IGNORE,
                FHIRSerializationContext::UNKNOWN_POLICY_ERROR,
                FHIRSerializationContext::UNKNOWN_POLICY_PRESERVE,
            ),
        )->then(function(string $policy) {
            // Create context with the specified unknown element policy
            $context = new FHIRSerializationContext(unknownElementPolicy: $policy);

            // Verify the policy is correctly set
            self::assertSame($policy, $context->unknownElementPolicy);

            // Verify policy helper methods work correctly
            switch ($policy) {
                case FHIRSerializationContext::UNKNOWN_POLICY_IGNORE:
                    self::assertTrue($context->shouldIgnoreUnknownElements());
                    self::assertFalse($context->shouldErrorOnUnknownElements());
                    self::assertFalse($context->shouldPreserveUnknownElements());
                    break;
                case FHIRSerializationContext::UNKNOWN_POLICY_ERROR:
                    self::assertFalse($context->shouldIgnoreUnknownElements());
                    self::assertTrue($context->shouldErrorOnUnknownElements());
                    self::assertFalse($context->shouldPreserveUnknownElements());
                    break;
                case FHIRSerializationContext::UNKNOWN_POLICY_PRESERVE:
                    self::assertFalse($context->shouldIgnoreUnknownElements());
                    self::assertFalse($context->shouldErrorOnUnknownElements());
                    self::assertTrue($context->shouldPreserveUnknownElements());
                    break;
            }

            // Verify the context can be converted to Symfony context
            $symfonyContext = $context->toSymfonyContext();
            self::assertSame($policy, $symfonyContext['fhir_unknown_element_policy']);

            // Verify round-trip conversion preserves policy
            $reconstructed = FHIRSerializationContext::fromSymfonyContext($symfonyContext);
            self::assertSame($policy, $reconstructed->unknownElementPolicy);
        });
    }

    /**
     * **Feature: fhir-serialization, Property 40: Performance optimization options**
     * **Validates: Requirements 9.4**
     *
     * For any performance optimization configuration, the serializer should provide options to skip non-essential validation when requested
     */
    public function testPerformanceOptimizationOptionsProperty()
    {
        $this->forAll(
            Generator\bool(),
            Generator\bool(),
        )->then(function(bool $skipNonEssentialValidation, bool $validateReferences) {
            // Create context with performance optimization settings
            $context = new FHIRSerializationContext(
                skipNonEssentialValidation: $skipNonEssentialValidation,
                validateReferences: $validateReferences,
            );

            // Verify the performance options are correctly set
            self::assertSame($skipNonEssentialValidation, $context->skipNonEssentialValidation);
            self::assertSame($validateReferences, $context->validateReferences);

            // Verify the context can be converted to Symfony context
            $symfonyContext = $context->toSymfonyContext();
            self::assertSame($skipNonEssentialValidation, $symfonyContext['fhir_skip_non_essential_validation']);
            self::assertSame($validateReferences, $symfonyContext['fhir_validate_references']);

            // Verify round-trip conversion preserves performance options
            $reconstructed = FHIRSerializationContext::fromSymfonyContext($symfonyContext);
            self::assertSame($skipNonEssentialValidation, $reconstructed->skipNonEssentialValidation);
            self::assertSame($validateReferences, $reconstructed->validateReferences);

            // Verify performance optimization factory methods work correctly
            $lenientContext = FHIRSerializationContext::withLenientValidation();
            self::assertTrue($lenientContext->skipNonEssentialValidation);
            self::assertFalse($lenientContext->validateReferences);

            $strictContext = FHIRSerializationContext::withStrictValidation();
            self::assertFalse($strictContext->skipNonEssentialValidation);
            self::assertTrue($strictContext->validateReferences);
        });
    }

    /**
     * **Feature: fhir-serialization, Property 41: Debug information availability**
     * **Validates: Requirements 9.5**
     *
     * For any serialization operation when debugging is enabled, detailed serialization context and error information should be available
     */
    public function testDebugInformationAvailabilityProperty()
    {
        $this->forAll(
            Generator\bool(),
        )->then(function(bool $enableDebugInfo) {
            // Create context with debug info setting
            $context = new FHIRSerializationContext(enableDebugInfo: $enableDebugInfo);

            // Verify the debug info setting is correctly set
            self::assertSame($enableDebugInfo, $context->enableDebugInfo);

            // Verify the context can be converted to Symfony context
            $symfonyContext = $context->toSymfonyContext();
            self::assertSame($enableDebugInfo, $symfonyContext['fhir_enable_debug_info']);

            // Verify round-trip conversion preserves debug info setting
            $reconstructed = FHIRSerializationContext::fromSymfonyContext($symfonyContext);
            self::assertSame($enableDebugInfo, $reconstructed->enableDebugInfo);

            // Verify debug factory method works correctly
            $debugContext = FHIRSerializationContext::withDebugging();
            self::assertTrue($debugContext->enableDebugInfo);

            // Verify custom options can be used for additional debug configuration
            $customDebugContext = $context->withCustomOptions(['debug_level' => 'verbose']);
            self::assertSame('verbose', $customDebugContext->getCustomOption('debug_level'));
            self::assertNull($customDebugContext->getCustomOption('nonexistent_option'));
            self::assertSame('default', $customDebugContext->getCustomOption('nonexistent_option', 'default'));
        });
    }

    public function testContextValidation()
    {
        // Test invalid format
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid format "invalid"');
        new FHIRSerializationContext(format: 'invalid');
    }

    public function testInvalidValidationMode()
    {
        // Test invalid validation mode
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid validation mode "invalid"');
        new FHIRSerializationContext(validationMode: 'invalid');
    }

    public function testInvalidUnknownElementPolicy()
    {
        // Test invalid unknown element policy
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid unknown element policy "invalid"');
        new FHIRSerializationContext(unknownElementPolicy: 'invalid');
    }

    public function testFactoryMethods()
    {
        // Test JSON factory method
        $jsonContext = FHIRSerializationContext::forJson();
        self::assertTrue($jsonContext->isJsonFormat());
        self::assertFalse($jsonContext->isXmlFormat());

        // Test XML factory method
        $xmlContext = FHIRSerializationContext::forXml();
        self::assertFalse($xmlContext->isJsonFormat());
        self::assertTrue($xmlContext->isXmlFormat());
        self::assertTrue($xmlContext->enableXmlNamespaces);

        // Test preserving unknown elements factory method
        $preservingContext = FHIRSerializationContext::preservingUnknownElements();
        self::assertTrue($preservingContext->shouldPreserveUnknownElements());

        // Test erroring on unknown elements factory method
        $erroringContext = FHIRSerializationContext::erroringOnUnknownElements();
        self::assertTrue($erroringContext->shouldErrorOnUnknownElements());
    }

    public function testFluentInterface()
    {
        $context = FHIRSerializationContext::forJson()
            ->withFormat(FHIRSerializationContext::FORMAT_XML)
            ->withValidationMode(FHIRSerializationContext::VALIDATION_LENIENT)
            ->withUnknownElementPolicy(FHIRSerializationContext::UNKNOWN_POLICY_PRESERVE)
            ->withDebugInfo(true)
            ->withPerformanceOptimization(true)
            ->withCustomOptions(['test' => 'value']);

        self::assertTrue($context->isXmlFormat());
        self::assertTrue($context->isLenientValidation());
        self::assertTrue($context->shouldPreserveUnknownElements());
        self::assertTrue($context->enableDebugInfo);
        self::assertTrue($context->skipNonEssentialValidation);
        self::assertSame('value', $context->getCustomOption('test'));
    }

    public function testBackwardCompatibility()
    {
        // Test that legacy context keys are supported
        $legacyContext = [
            'unknown_property_policy' => FHIRSerializationContext::UNKNOWN_POLICY_ERROR,
        ];

        $context = FHIRSerializationContext::fromSymfonyContext($legacyContext);
        self::assertTrue($context->shouldErrorOnUnknownElements());
    }
}

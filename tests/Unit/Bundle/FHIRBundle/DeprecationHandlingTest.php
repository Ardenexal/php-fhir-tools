<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Tests\Unit\Bundle\FHIRBundle;

use Ardenexal\FHIRTools\Bundle\FHIRBundle\Compatibility\SymfonyVersionHelper;
use Eris\Generator;
use Eris\TestTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Property-based test for deprecation handling across Symfony versions.
 *
 * **Feature: repository-reorganization, Property 12: Deprecation handling**
 *
 * Tests that the FHIR bundle handles deprecated features gracefully
 * and provides appropriate fallbacks for older Symfony versions.
 */
class DeprecationHandlingTest extends TestCase
{
    use TestTrait;

    /**
     * Test that deprecated features are handled gracefully.
     *
     * **Feature: repository-reorganization, Property 12: Deprecation handling**
     * **Validates: Requirements 3.5**
     *
     * Property: For any deprecated Symfony feature, the bundle should
     * handle it gracefully without breaking functionality.
     */
    public function testDeprecatedFeatureHandling(): void
    {
        $this->forAll(
            Generator\elements([
                'old_serializer_interface',
                'legacy_di_configuration',
                'deprecated_console_helpers',
                'old_configuration_syntax',
            ]), // Simulated deprecated features
        )->then(function(string $deprecatedFeature): void {
            // Test that the version helper can handle deprecated features gracefully
            $result = SymfonyVersionHelper::callMethodSafely(
                new \stdClass(),
                'nonExistentMethod',
                ['arg1', 'arg2'],
            );

            // Should return null for non-existent methods without throwing exceptions
            self::assertNull($result, 'Non-existent methods should return null gracefully');

            // Test that version-specific configurations handle deprecations
            $versionConfig = SymfonyVersionHelper::getVersionSpecificServiceConfig();
            self::assertIsArray($versionConfig, 'Version config should always be an array');

            // Verify that configuration adapts based on Symfony version
            if (SymfonyVersionHelper::isSymfony70OrHigher()) {
                // Newer versions should use new features
                if (isset($versionConfig['use_new_serializer_features'])) {
                    self::assertTrue($versionConfig['use_new_serializer_features']);
                }
                if (isset($versionConfig['use_new_di_features'])) {
                    self::assertTrue($versionConfig['use_new_di_features']);
                }
            } else {
                // Older versions should use legacy features
                if (isset($versionConfig['use_new_serializer_features'])) {
                    self::assertFalse($versionConfig['use_new_serializer_features']);
                }
                if (isset($versionConfig['use_new_di_features'])) {
                    self::assertFalse($versionConfig['use_new_di_features']);
                }
            }
        });
    }

    /**
     * Test that attribute vs annotation configuration is handled correctly.
     *
     * **Feature: repository-reorganization, Property 12: Deprecation handling**
     * **Validates: Requirements 3.5**
     *
     * Property: The bundle should use attributes in newer Symfony versions
     * and fall back to annotations in older versions.
     */
    public function testAttributeAnnotationFallback(): void
    {
        $attributeConfig = SymfonyVersionHelper::getCompatibleAttributeConfig();

        self::assertIsArray($attributeConfig, 'Attribute config should be an array');
        self::assertArrayHasKey('use_attributes', $attributeConfig);
        self::assertArrayHasKey('use_annotations', $attributeConfig);

        // Test that the configuration is consistent
        if ($attributeConfig['use_attributes']) {
            self::assertFalse($attributeConfig['use_annotations'], 'Should not use both attributes and annotations');
        } else {
            self::assertTrue($attributeConfig['use_annotations'], 'Should use annotations when not using attributes');
        }

        // Test that the choice is based on Symfony version
        if (SymfonyVersionHelper::isSymfony70OrHigher()) {
            self::assertTrue($attributeConfig['use_attributes'], 'Symfony 7.0+ should use attributes');
        } else {
            self::assertFalse($attributeConfig['use_attributes'], 'Symfony < 7.0 should not use attributes');
        }
    }

    /**
     * Test that version-specific code paths work correctly.
     *
     * **Feature: repository-reorganization, Property 12: Deprecation handling**
     * **Validates: Requirements 3.5**
     *
     * Property: Version-specific code paths should execute correctly
     * without causing errors in any supported Symfony version.
     */
    public function testVersionSpecificCodePaths(): void
    {
        $this->forAll(
            Generator\elements([
                'new_serializer_normalizer_interface',
                'improved_di_container',
                'enhanced_configuration_validation',
                'new_console_attributes',
            ]),
        )->then(function(string $feature): void {
            $hasFeature = SymfonyVersionHelper::hasFeature($feature);

            // Feature detection should be consistent
            self::assertIsBool($hasFeature, 'Feature detection should return boolean');

            // Test that feature availability matches version expectations
            $currentVersion = Kernel::VERSION;

            switch ($feature) {
                case 'new_serializer_normalizer_interface':
                case 'improved_di_container':
                case 'new_console_attributes':
                    $expectedAvailable = version_compare($currentVersion, '7.0.0', '>=');
                    break;
                case 'enhanced_configuration_validation':
                    $expectedAvailable = version_compare($currentVersion, '7.4.0', '>=');
                    break;
                default:
                    $expectedAvailable = false;
            }

            self::assertEquals(
                $expectedAvailable,
                $hasFeature,
                "Feature '{$feature}' availability should match Symfony version {$currentVersion}",
            );
        });
    }

    /**
     * Test that error handling works across Symfony versions.
     *
     * **Feature: repository-reorganization, Property 12: Deprecation handling**
     * **Validates: Requirements 3.5**
     *
     * Property: Error handling should work consistently across
     * different Symfony versions without breaking.
     */
    public function testErrorHandlingCompatibility(): void
    {
        // Test that method calls on non-existent methods are handled gracefully
        $testObject = new class () {
            public function existingMethod(): string
            {
                return 'success';
            }
        };

        // Existing method should work
        $result = SymfonyVersionHelper::callMethodSafely($testObject, 'existingMethod');
        self::assertEquals('success', $result);

        // Non-existent method should return null without throwing
        $result = SymfonyVersionHelper::callMethodSafely($testObject, 'nonExistentMethod');
        self::assertNull($result);

        // Method with arguments should work
        $testObjectWithArgs = new class () {
            public function methodWithArgs(string $arg1, int $arg2): string
            {
                return $arg1 . $arg2;
            }
        };

        $result = SymfonyVersionHelper::callMethodSafely(
            $testObjectWithArgs,
            'methodWithArgs',
            ['test', 123],
        );
        self::assertEquals('test123', $result);
    }

    /**
     * Test that version detection is robust.
     *
     * **Feature: repository-reorganization, Property 12: Deprecation handling**
     * **Validates: Requirements 3.5**
     *
     * Property: Version detection should work reliably and handle
     * edge cases in version strings.
     */
    public function testVersionDetectionRobustness(): void
    {
        $detectedVersion = SymfonyVersionHelper::getSymfonyVersion();

        // Should match Symfony's own version detection
        self::assertEquals(Kernel::VERSION, $detectedVersion);

        // Version should be a valid semantic version string
        self::assertMatchesRegularExpression(
            '/^\d+\.\d+\.\d+/',
            $detectedVersion,
            'Version should start with semantic version pattern',
        );

        // Version comparison methods should be consistent
        $is64OrHigher = SymfonyVersionHelper::isSymfony64OrHigher();
        $is70OrHigher = SymfonyVersionHelper::isSymfony70OrHigher();
        $is74OrHigher = SymfonyVersionHelper::isSymfony74OrHigher();

        // Logical consistency checks
        if ($is74OrHigher) {
            self::assertTrue($is70OrHigher, 'Symfony 7.4+ should also be 7.0+');
            self::assertTrue($is64OrHigher, 'Symfony 7.4+ should also be 6.4+');
        }

        if ($is70OrHigher) {
            self::assertTrue($is64OrHigher, 'Symfony 7.0+ should also be 6.4+');
        }

        // Test with actual version comparisons
        $actualVersion = Kernel::VERSION;
        self::assertEquals(
            version_compare($actualVersion, '6.4.0', '>='),
            $is64OrHigher,
            'isSymfony64OrHigher should match version_compare result',
        );

        self::assertEquals(
            version_compare($actualVersion, '7.0.0', '>='),
            $is70OrHigher,
            'isSymfony70OrHigher should match version_compare result',
        );

        self::assertEquals(
            version_compare($actualVersion, '7.4.0', '>='),
            $is74OrHigher,
            'isSymfony74OrHigher should match version_compare result',
        );
    }
}

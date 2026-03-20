<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Bundle\FHIRBundle\Compatibility;

use Symfony\Component\HttpKernel\Kernel;

/**
 * Symfony Version Compatibility Helper.
 *
 * Provides utilities for handling differences between Symfony 6.4 and 7.4
 * to ensure cross-compatibility of the FHIR bundle.
 *
 * @author Ardenexal FHIRTools Team
 */
class SymfonyVersionHelper
{
    /**
     * Get the current Symfony version.
     */
    public static function getSymfonyVersion(): string
    {
        return Kernel::VERSION;
    }

    /**
     * Check if the current Symfony version is 6.4 or higher.
     */
    public static function isSymfony64OrHigher(): bool
    {
        return version_compare(self::getSymfonyVersion(), '6.4.0', '>=');
    }

    /**
     * Check if the current Symfony version is 7.0 or higher.
     */
    public static function isSymfony70OrHigher(): bool
    {
        return version_compare(self::getSymfonyVersion(), '7.0.0', '>=');
    }

    /**
     * Check if the current Symfony version is 7.4 or higher.
     */
    public static function isSymfony74OrHigher(): bool
    {
        return version_compare(self::getSymfonyVersion(), '7.4.0', '>=');
    }

    /**
     * Get version-specific service configuration adjustments.
     *
     * @return array<string, mixed>
     */
    public static function getVersionSpecificServiceConfig(): array
    {
        $config = [];

        // Handle deprecated features gracefully
        if (self::isSymfony70OrHigher()) {
            // Symfony 7.0+ specific configurations
            $config['use_new_serializer_features'] = true;
            $config['use_new_di_features']         = true;
        } else {
            // Symfony 6.4 compatibility configurations
            $config['use_new_serializer_features'] = false;
            $config['use_new_di_features']         = false;
        }

        return $config;
    }

    /**
     * Handle deprecated method calls gracefully.
     *
     * @param object       $object
     * @param string       $method
     * @param array<mixed> $arguments
     *
     * @return mixed
     */
    public static function callMethodSafely(object $object, string $method, array $arguments = []): mixed
    {
        if (method_exists($object, $method)) {
            $callable = [$object, $method];
            if (is_callable($callable)) {
                return call_user_func_array($callable, $arguments);
            }
        }

        // Handle deprecated methods or provide fallbacks
        return null;
    }

    /**
     * Get compatible attribute configuration for services.
     *
     * @return array<string, mixed>
     */
    public static function getCompatibleAttributeConfig(): array
    {
        if (self::isSymfony70OrHigher()) {
            // Use newer attribute-based configuration
            return [
                'use_attributes'  => true,
                'use_annotations' => false,
            ];
        }

        // Fall back to annotation-based configuration for older versions
        return [
            'use_attributes'  => false,
            'use_annotations' => true,
        ];
    }

    /**
     * Check if a specific Symfony feature is available.
     */
    public static function hasFeature(string $feature): bool
    {
        return match ($feature) {
            'new_serializer_normalizer_interface' => self::isSymfony70OrHigher(),
            'improved_di_container'               => self::isSymfony70OrHigher(),
            'enhanced_configuration_validation'   => self::isSymfony74OrHigher(),
            'new_console_attributes'              => self::isSymfony70OrHigher(),
            default                               => false,
        };
    }
}

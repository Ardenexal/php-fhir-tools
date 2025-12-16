<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Exception;

use Exception;

/**
 * Exception thrown during FHIR package operations
 *
 * This exception is thrown when errors occur during package loading,
 * parsing, or management operations.
 *
 * @author FHIR Tools
 * @since 1.0.0
 */
class PackageException extends Exception
{
    /**
     * Additional context information about the error
     *
     * @var array<string, mixed>
     */
    private array $context;

    /**
     * Create a new PackageException
     *
     * @param string $message The error message
     * @param array<string, mixed> $context Additional context information
     * @param int $code The error code
     * @param Exception|null $previous The previous exception
     */
    public function __construct(string $message, array $context = [], int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get the additional context information
     *
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Create exception for package not found
     *
     * @param string $packageName The package name
     * @param string|null $version The package version
     * @return self
     */
    public static function packageNotFound(string $packageName, ?string $version = null): self
    {
        $message = "Package '{$packageName}' not found";
        if ($version !== null) {
            $message .= " (version: {$version})";
        }

        return new self($message, [
            'package_name' => $packageName,
            'version' => $version
        ]);
    }

    /**
     * Create exception for invalid package format
     *
     * @param string $packageName The package name
     * @param string $reason The reason for invalidity
     * @return self
     */
    public static function invalidPackageFormat(string $packageName, string $reason): self
    {
        return new self(
            "Invalid package format for '{$packageName}': {$reason}",
            [
                'package_name' => $packageName,
                'reason' => $reason
            ]
        );
    }

    /**
     * Create exception for package download failure
     *
     * @param string $packageName The package name
     * @param string $url The download URL
     * @param string $reason The failure reason
     * @return self
     */
    public static function downloadFailed(string $packageName, string $url, string $reason): self
    {
        return new self(
            "Failed to download package '{$packageName}' from '{$url}': {$reason}",
            [
                'package_name' => $packageName,
                'url' => $url,
                'reason' => $reason
            ]
        );
    }

    /**
     * Create exception for dependency resolution failure
     *
     * @param string $packageName The package name
     * @param array<string> $missingDependencies The missing dependencies
     * @return self
     */
    public static function dependencyResolutionFailed(string $packageName, array $missingDependencies): self
    {
        return new self(
            "Failed to resolve dependencies for package '{$packageName}': " . implode(', ', $missingDependencies),
            [
                'package_name' => $packageName,
                'missing_dependencies' => $missingDependencies
            ]
        );
    }
}
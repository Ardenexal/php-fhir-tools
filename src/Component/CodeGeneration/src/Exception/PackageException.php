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
 *
 * @since 1.0.0
 */
class PackageException extends \Exception
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
     * @param string               $message  The error message
     * @param array<string, mixed> $context  Additional context information
     * @param int                  $code     The error code
     * @param \Exception|null      $previous The previous exception
     */
    public function __construct(string $message, array $context = [], int $code = 0, ?\Exception $previous = null)
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
     * @param string      $packageName The package name
     * @param string|null $version     The package version
     *
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
            'version'      => $version,
        ]);
    }

    /**
     * Create exception for invalid package format
     *
     * @param string $packageName The package name
     * @param string $reason      The reason for invalidity
     *
     * @return self
     */
    public static function invalidPackageFormat(string $packageName, string $reason): self
    {
        return new self(
            "Invalid package format for '{$packageName}': {$reason}",
            [
                'package_name' => $packageName,
                'reason'       => $reason,
            ],
        );
    }

    /**
     * Create exception for package download failure
     *
     * @param string $packageName The package name
     * @param string $url         The download URL
     * @param string $reason      The failure reason
     *
     * @return self
     */
    public static function downloadFailed(string $packageName, string $url, string $reason): self
    {
        return new self(
            "Failed to download package '{$packageName}' from '{$url}': {$reason}",
            [
                'package_name' => $packageName,
                'url'          => $url,
                'reason'       => $reason,
            ],
        );
    }

    /**
     * Create exception for dependency resolution failure
     *
     * @param string        $packageName         The package name
     * @param array<string> $missingDependencies The missing dependencies
     *
     * @return self
     */
    public static function dependencyResolutionFailed(string $packageName, array $missingDependencies): self
    {
        return new self(
            "Failed to resolve dependencies for package '{$packageName}': " . implode(', ', $missingDependencies),
            [
                'package_name'         => $packageName,
                'missing_dependencies' => $missingDependencies,
            ],
        );
    }

    /**
     * Create exception for file not found
     *
     * @param string $filePath The file path that was not found
     *
     * @return self
     */
    public static function fileNotFound(string $filePath): self
    {
        return new self(
            "File not found: {$filePath}",
            ['file_path' => $filePath],
        );
    }

    /**
     * Create exception for unsupported hash algorithm
     *
     * @param string $algorithm The unsupported algorithm
     *
     * @return self
     */
    public static function unsupportedHashAlgorithm(string $algorithm): self
    {
        return new self(
            "Unsupported hash algorithm: {$algorithm}",
            ['algorithm' => $algorithm],
        );
    }

    /**
     * Create exception for checksum generation failure
     *
     * @param string $filePath The file path
     * @param string $reason   The failure reason
     *
     * @return self
     */
    public static function checksumGenerationFailed(string $filePath, string $reason): self
    {
        return new self(
            "Failed to generate checksum for '{$filePath}': {$reason}",
            [
                'file_path' => $filePath,
                'reason'    => $reason,
            ],
        );
    }

    /**
     * Create exception for integrity metadata store failure
     *
     * @param string $packageIdentifier The package identifier
     * @param string $reason            The failure reason
     *
     * @return self
     */
    public static function integrityMetadataStoreFailed(string $packageIdentifier, string $reason): self
    {
        return new self(
            "Failed to store integrity metadata for '{$packageIdentifier}': {$reason}",
            [
                'package_identifier' => $packageIdentifier,
                'reason'             => $reason,
            ],
        );
    }

    /**
     * Create exception for integrity metadata load failure
     *
     * @param string $cacheDir The cache directory
     * @param string $reason   The failure reason
     *
     * @return self
     */
    public static function integrityMetadataLoadFailed(string $cacheDir, string $reason): self
    {
        return new self(
            "Failed to load integrity metadata from '{$cacheDir}': {$reason}",
            [
                'cache_dir' => $cacheDir,
                'reason'    => $reason,
            ],
        );
    }

    /**
     * Create exception for cache cleanup failure
     *
     * @param string $cacheDir The cache directory
     * @param string $reason   The failure reason
     *
     * @return self
     */
    public static function cacheCleanupFailed(string $cacheDir, string $reason): self
    {
        return new self(
            "Failed to clean up cache directory '{$cacheDir}': {$reason}",
            [
                'cache_dir' => $cacheDir,
                'reason'    => $reason,
            ],
        );
    }

    /**
     * Create exception for invalid package response
     *
     * @param string $packageName The package name
     * @param string $reason      The failure reason
     *
     * @return self
     */
    public static function invalidPackageResponse(string $packageName, string $reason): self
    {
        return new self(
            "Invalid package response for '{$packageName}': {$reason}",
            [
                'package_name' => $packageName,
                'reason'       => $reason,
            ],
        );
    }

    /**
     * Create exception for package extraction failure
     *
     * @param string $packageName The package name
     * @param string $version     The package version
     * @param string $reason      The failure reason
     *
     * @return self
     */
    public static function extractionFailed(string $packageName, string $version, string $reason): self
    {
        return new self(
            "Failed to extract package '{$packageName}@{$version}': {$reason}",
            [
                'package_name' => $packageName,
                'version'      => $version,
                'reason'       => $reason,
            ],
        );
    }

    /**
     * Create exception for unsupported FHIR version
     *
     * @param string        $version           The unsupported version
     * @param array<string> $supportedVersions The supported versions
     *
     * @return self
     */
    public static function unsupportedFhirVersion(string $version, array $supportedVersions): self
    {
        return new self(
            "Unsupported FHIR version '{$version}'. Supported versions: " . implode(', ', $supportedVersions),
            [
                'version'            => $version,
                'supported_versions' => $supportedVersions,
            ],
        );
    }

    /**
     * Create exception for package not available offline
     *
     * @param string      $packageName The package name
     * @param string|null $version     The package version
     *
     * @return self
     */
    public static function packageNotAvailableOffline(string $packageName, ?string $version = null): self
    {
        $message = "Package '{$packageName}' is not available in cache and offline mode is enabled";
        if ($version !== null) {
            $message .= " (version: {$version})";
        }

        return new self($message, [
            'package_name' => $packageName,
            'version'      => $version,
            'offline_mode' => true,
        ]);
    }
}

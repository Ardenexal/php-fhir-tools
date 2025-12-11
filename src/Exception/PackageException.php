<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Exception;

/**
 * Exception thrown when package loading or validation fails
 *
 * This exception is used for all package-related errors including:
 * - Package not found in registry
 * - Invalid package responses from registry
 * - Download failures (network issues, HTTP errors)
 * - Package extraction failures (corrupted archives, filesystem issues)
 * - Invalid package structure (missing files, malformed metadata)
 *
 * Each static factory method provides specific context information
 * to help with debugging and error reporting.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class PackageException extends FHIRToolsException
{
    /**
     * Create exception for package not found in registry
     *
     * This method creates an exception when a requested package cannot be
     * found in the FHIR package registry. It includes the package name and
     * optional version in both the message and context.
     *
     * @param string      $packageName The name of the package that was not found
     * @param string|null $version     The specific version requested (optional)
     *
     * @return self The configured exception instance
     */
    public static function packageNotFound(string $packageName, ?string $version = null): self
    {
        $message = "Package '{$packageName}'" . ($version ? " version '{$version}'" : '') . ' not found';

        return new self($message, 404, null, [
            'package_name' => $packageName,
            'version'      => $version,
            'error_type'   => 'package_not_found',
        ]);
    }

    /**
     * Create exception for invalid package registry response
     *
     * This method creates an exception when the package registry returns
     * an invalid or malformed response that cannot be processed.
     *
     * @param string $packageName The name of the package being requested
     * @param string $reason      The specific reason why the response is invalid
     *
     * @return self The configured exception instance
     */
    public static function invalidPackageResponse(string $packageName, string $reason): self
    {
        return new self(
            "Invalid package response for '{$packageName}': {$reason}",
            400,
            null,
            [
                'package_name' => $packageName,
                'reason'       => $reason,
                'error_type'   => 'invalid_response',
            ],
        );
    }

    /**
     * Create exception for package download failure
     *
     * This method creates an exception when downloading a package fails,
     * typically due to network issues or HTTP errors from the registry.
     *
     * @param string $packageName The name of the package being downloaded
     * @param string $version     The version of the package being downloaded
     * @param int    $statusCode  The HTTP status code returned by the server
     *
     * @return self The configured exception instance
     */
    public static function downloadFailed(string $packageName, string $version, int $statusCode): self
    {
        return new self(
            "Failed to download package '{$packageName}' version '{$version}' (HTTP {$statusCode})",
            $statusCode,
            null,
            [
                'package_name' => $packageName,
                'version'      => $version,
                'http_status'  => $statusCode,
                'error_type'   => 'download_failed',
            ],
        );
    }

    /**
     * Create exception for package extraction failure
     *
     * This method creates an exception when extracting a downloaded package
     * fails, typically due to corrupted archives or filesystem permissions.
     *
     * @param string $packageName The name of the package being extracted
     * @param string $version     The version of the package being extracted
     * @param string $reason      The specific reason why extraction failed
     *
     * @return self The configured exception instance
     */
    public static function extractionFailed(string $packageName, string $version, string $reason): self
    {
        return new self(
            "Failed to extract package '{$packageName}' version '{$version}': {$reason}",
            500,
            null,
            [
                'package_name' => $packageName,
                'version'      => $version,
                'reason'       => $reason,
                'error_type'   => 'extraction_failed',
            ],
        );
    }

    /**
     * Create exception for invalid package structure
     *
     * This method creates an exception when a package has an invalid structure,
     * such as missing required files (package.json) or malformed metadata.
     *
     * @param string $packageName The name of the package with invalid structure
     * @param string $reason      The specific reason why the structure is invalid
     *
     * @return self The configured exception instance
     */
    public static function invalidPackageStructure(string $packageName, string $reason): self
    {
        return new self(
            "Invalid package structure for '{$packageName}': {$reason}",
            400,
            null,
            [
                'package_name' => $packageName,
                'reason'       => $reason,
                'error_type'   => 'invalid_structure',
            ],
        );
    }

    /**
     * Create exception for no versions available
     *
     * @param string $constraint Version constraint that couldn't be satisfied
     *
     * @return self The configured exception instance
     */
    public static function noVersionsAvailable(string $constraint): self
    {
        return new self(
            "No versions available to satisfy constraint '{$constraint}'",
            404,
            null,
            [
                'constraint' => $constraint,
                'error_type' => 'no_versions_available',
            ],
        );
    }

    /**
     * Create exception for no valid versions available
     *
     * @param string $constraint Version constraint that couldn't be satisfied
     *
     * @return self The configured exception instance
     */
    public static function noValidVersionsAvailable(string $constraint): self
    {
        return new self(
            "No valid versions available to satisfy constraint '{$constraint}'",
            404,
            null,
            [
                'constraint' => $constraint,
                'error_type' => 'no_valid_versions_available',
            ],
        );
    }

    /**
     * Create exception for no version satisfying constraint
     *
     * @param string        $constraint        Version constraint
     * @param array<string> $availableVersions Available versions
     *
     * @return self The configured exception instance
     */
    public static function noVersionSatisfiesConstraint(string $constraint, array $availableVersions): self
    {
        return new self(
            "No version satisfies constraint '{$constraint}' from available versions: " . implode(', ', $availableVersions),
            404,
            null,
            [
                'constraint'          => $constraint,
                'available_versions'  => $availableVersions,
                'error_type'          => 'no_version_satisfies_constraint',
            ],
        );
    }

    /**
     * Create exception for invalid version constraint
     *
     * @param string $constraint Invalid constraint
     * @param string $reason     Reason why constraint is invalid
     *
     * @return self The configured exception instance
     */
    public static function invalidVersionConstraint(string $constraint, string $reason): self
    {
        return new self(
            "Invalid version constraint '{$constraint}': {$reason}",
            400,
            null,
            [
                'constraint' => $constraint,
                'reason'     => $reason,
                'error_type' => 'invalid_version_constraint',
            ],
        );
    }

    /**
     * Create exception for circular dependency
     *
     * @param string        $packageName    Package causing circular dependency
     * @param array<string> $dependencyPath Dependency path showing the cycle
     *
     * @return self The configured exception instance
     */
    public static function circularDependency(string $packageName, array $dependencyPath): self
    {
        $pathString = implode(' -> ', $dependencyPath) . ' -> ' . $packageName;

        return new self(
            "Circular dependency detected: {$pathString}",
            400,
            null,
            [
                'package_name'     => $packageName,
                'dependency_path'  => $dependencyPath,
                'error_type'       => 'circular_dependency',
            ],
        );
    }

    /**
     * Create exception for dependency not found
     *
     * @param string $dependencyName Dependency name
     * @param string $constraint     Version constraint
     *
     * @return self The configured exception instance
     */
    public static function dependencyNotFound(string $dependencyName, string $constraint): self
    {
        return new self(
            "Dependency '{$dependencyName}' with constraint '{$constraint}' not found",
            404,
            null,
            [
                'dependency_name' => $dependencyName,
                'constraint'      => $constraint,
                'error_type'      => 'dependency_not_found',
            ],
        );
    }

    /**
     * Create exception for dependency version not found
     *
     * @param string $dependencyName Dependency name
     * @param string $version        Required version
     *
     * @return self The configured exception instance
     */
    public static function dependencyVersionNotFound(string $dependencyName, string $version): self
    {
        return new self(
            "Dependency '{$dependencyName}' version '{$version}' not found",
            404,
            null,
            [
                'dependency_name' => $dependencyName,
                'version'         => $version,
                'error_type'      => 'dependency_version_not_found',
            ],
        );
    }

    /**
     * Create exception for file not found
     *
     * @param string $filePath File path that was not found
     *
     * @return self The configured exception instance
     */
    public static function fileNotFound(string $filePath): self
    {
        return new self(
            "File not found: {$filePath}",
            404,
            null,
            [
                'file_path'  => $filePath,
                'error_type' => 'file_not_found',
            ],
        );
    }

    /**
     * Create exception for unsupported hash algorithm
     *
     * @param string $algorithm Unsupported algorithm
     *
     * @return self The configured exception instance
     */
    public static function unsupportedHashAlgorithm(string $algorithm): self
    {
        return new self(
            "Unsupported hash algorithm: {$algorithm}",
            400,
            null,
            [
                'algorithm'  => $algorithm,
                'error_type' => 'unsupported_hash_algorithm',
            ],
        );
    }

    /**
     * Create exception for checksum generation failure
     *
     * @param string $filePath File path
     * @param string $reason   Failure reason
     *
     * @return self The configured exception instance
     */
    public static function checksumGenerationFailed(string $filePath, string $reason): self
    {
        return new self(
            "Failed to generate checksum for '{$filePath}': {$reason}",
            500,
            null,
            [
                'file_path'  => $filePath,
                'reason'     => $reason,
                'error_type' => 'checksum_generation_failed',
            ],
        );
    }

    /**
     * Create exception for integrity metadata store failure
     *
     * @param string $packageIdentifier Package identifier
     * @param string $reason            Failure reason
     *
     * @return self The configured exception instance
     */
    public static function integrityMetadataStoreFailed(string $packageIdentifier, string $reason): self
    {
        return new self(
            "Failed to store integrity metadata for '{$packageIdentifier}': {$reason}",
            500,
            null,
            [
                'package_identifier' => $packageIdentifier,
                'reason'             => $reason,
                'error_type'         => 'integrity_metadata_store_failed',
            ],
        );
    }

    /**
     * Create exception for integrity metadata load failure
     *
     * @param string $cacheDir Cache directory
     * @param string $reason   Failure reason
     *
     * @return self The configured exception instance
     */
    public static function integrityMetadataLoadFailed(string $cacheDir, string $reason): self
    {
        return new self(
            "Failed to load integrity metadata from '{$cacheDir}': {$reason}",
            500,
            null,
            [
                'cache_dir'  => $cacheDir,
                'reason'     => $reason,
                'error_type' => 'integrity_metadata_load_failed',
            ],
        );
    }

    /**
     * Create exception for cache cleanup failure
     *
     * @param string $cacheDir Cache directory
     * @param string $reason   Failure reason
     *
     * @return self The configured exception instance
     */
    public static function cacheCleanupFailed(string $cacheDir, string $reason): self
    {
        return new self(
            "Failed to cleanup cache directory '{$cacheDir}': {$reason}",
            500,
            null,
            [
                'cache_dir'  => $cacheDir,
                'reason'     => $reason,
                'error_type' => 'cache_cleanup_failed',
            ],
        );
    }

    /**
     * Create exception for unsupported FHIR version
     *
     * @param string        $fhirVersion       Unsupported FHIR version
     * @param array<string> $supportedVersions List of supported versions
     *
     * @return self The configured exception instance
     */
    public static function unsupportedFhirVersion(string $fhirVersion, array $supportedVersions): self
    {
        return new self(
            "Unsupported FHIR version '{$fhirVersion}'. Supported versions: " . implode(', ', $supportedVersions),
            400,
            null,
            [
                'fhir_version'       => $fhirVersion,
                'supported_versions' => $supportedVersions,
                'error_type'         => 'unsupported_fhir_version',
            ],
        );
    }
}

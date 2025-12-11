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
}

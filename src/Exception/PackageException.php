<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Exception;

/**
 * Exception thrown when package-related operations fail
 *
 * This exception is thrown when FHIR package operations encounter errors,
 * such as package not found, download failures, or invalid package formats.
 *
 * @author FHIR Tools
 *
 * @since 1.0.0
 */
class PackageException extends FHIRToolsException
{
    /**
     * Create exception for package not found scenarios
     *
     * @param string $packageName The name of the package that was not found
     * @param string $version     The version that was requested
     *
     * @return static
     */
    public static function packageNotFound(string $packageName, string $version = 'latest'): static
    {
        $message = "Package '{$packageName}' version '{$version}' not found";

        return new static($message, 404, null, [
            'package_name' => $packageName,
            'version'      => $version,
            'error_type'   => 'package_not_found',
        ]);
    }

    /**
     * Create exception for package download failures
     *
     * @param string $packageName The name of the package that failed to download
     * @param string $version     The version that was requested
     * @param int    $httpStatus  The HTTP status code from the failed request
     *
     * @return static
     */
    public static function downloadFailed(string $packageName, string $version, int $httpStatus): static
    {
        $message = "Failed to download package '{$packageName}' version '{$version}' (HTTP {$httpStatus})";

        return new static($message, $httpStatus, null, [
            'package_name' => $packageName,
            'version'      => $version,
            'http_status'  => $httpStatus,
        ]);
    }
}

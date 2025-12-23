<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\Exception;

use Ardenexal\FHIRTools\Exception\FHIRToolsException;

/**
 * Base exception for FHIR Models component
 *
 * @author FHIR Tools Contributors
 *
 * @description Exception class for FHIR Models component-specific errors
 */
class ModelsException extends FHIRToolsException
{
    /**
     * Create exception for unsupported FHIR version
     *
     * @param string $version The unsupported version
     *
     * @return self
     */
    public static function unsupportedVersion(string $version): self
    {
        $supportedVersions = implode(', ', ['R4', 'R4B', 'R5']);

        return new self("Unsupported FHIR version: {$version}. Supported versions: {$supportedVersions}");
    }

    /**
     * Create exception for model class not found
     *
     * @param string $className The class name that was not found
     *
     * @return self
     */
    public static function modelNotFound(string $className): self
    {
        return new self("FHIR model class not found: {$className}");
    }

    /**
     * Create exception for invalid namespace
     *
     * @param string $namespace The invalid namespace
     *
     * @return self
     */
    public static function invalidNamespace(string $namespace): self
    {
        return new self("Invalid FHIR model namespace: {$namespace}");
    }
}

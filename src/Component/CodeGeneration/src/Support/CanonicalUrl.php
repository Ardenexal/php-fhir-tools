<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Support;

use Ardenexal\FHIRTools\Component\Metadata\CanonicalUrl as MetadataCanonicalUrl;

/**
 * Helpers for FHIR canonical URLs.
 *
 * @deprecated since 0.8, use {@see MetadataCanonicalUrl}, which validation and profile compilation share
 */
final class CanonicalUrl
{
    /**
     * Strip the `|<version>` suffix from a canonical URL, returning the bare URL.
     *
     * @see MetadataCanonicalUrl::stripVersion()
     */
    public static function stripVersion(string $url): string
    {
        return MetadataCanonicalUrl::stripVersion($url);
    }
}

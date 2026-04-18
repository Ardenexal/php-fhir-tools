<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization;

/**
 * Registry mapping IG extension URLs and profile URLs to their typed PHP classes.
 *
 * Populated at container compile time by FHIRIGRegistryCompilerPass when an IG output
 * directory is configured. Used by the normalizers and type resolver to resolve typed
 * IG classes during deserialization instead of falling back to the base Extension or
 * resource class.
 *
 * @author Ardenexal
 */
class FHIRIGTypeRegistry
{
    /**
     * @param array<string, class-string> $extensionMappings Extension URL → typed extension class
     * @param array<string, class-string> $profileMappings   Profile URL → typed profile class
     */
    public function __construct(
        private readonly array $extensionMappings = [],
        private readonly array $profileMappings = [],
    ) {
    }

    /**
     * Resolve the typed extension class for the given extension URL.
     *
     * Returns null when no typed class is registered for the URL,
     * in which case callers should fall back to the base Extension class.
     *
     * @return class-string|null
     */
    public function resolveExtensionClass(string $url): ?string
    {
        return $this->extensionMappings[$url] ?? null;
    }

    /**
     * Resolve the typed profile class for the given profile URL.
     *
     * Returns null when no typed class is registered for the URL,
     * in which case callers should fall back to the base resource class.
     *
     * @return class-string|null
     */
    public function resolveProfileClass(string $profileUrl): ?string
    {
        return $this->profileMappings[$profileUrl] ?? null;
    }

    /**
     * @return array<string, class-string>
     */
    public function getExtensionMappings(): array
    {
        return $this->extensionMappings;
    }

    /**
     * @return array<string, class-string>
     */
    public function getProfileMappings(): array
    {
        return $this->profileMappings;
    }
}

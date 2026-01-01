<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

/**
 * Resolves which profiles to validate a resource against.
 *
 * Profile selection follows this order:
 * 1. Explicit profiles (if provided)
 * 2. resource.meta.profile[] (if present)
 * 3. Configured default profiles (fallback)
 *
 * This implements the "intersection semantics" requirement where a resource
 * must satisfy ALL selected profiles.
 *
 * @author FHIR Tools
 */
class ProfileSelection
{
    /**
     * @param array<string, string|array<string>> $defaultProfiles Default profiles per resource type
     */
    public function __construct(
        private readonly array $defaultProfiles = []
    ) {
    }

    /**
     * Resolve profiles for a resource.
     *
     * @param array<string, mixed> $resource         The FHIR resource
     * @param array<string>|null   $explicitProfiles Explicitly requested profiles
     *
     * @return array<string> List of profile URLs to validate against
     */
    public function resolve(array $resource, ?array $explicitProfiles = null): array
    {
        // 1. Use explicit profiles if provided
        if ($explicitProfiles !== null && !empty($explicitProfiles)) {
            return array_values(array_unique($explicitProfiles));
        }

        // 2. Use meta.profile[] if present
        $metaProfiles = $this->extractMetaProfiles($resource);
        if (!empty($metaProfiles)) {
            return array_values(array_unique($metaProfiles));
        }

        // 3. Use configured default profiles
        $resourceType = $resource['resourceType'] ?? null;
        if ($resourceType && isset($this->defaultProfiles[$resourceType])) {
            $defaults = $this->defaultProfiles[$resourceType];

            return is_array($defaults) ? $defaults : [$defaults];
        }

        // No profiles to validate against
        return [];
    }

    /**
     * Extract profile URLs from resource.meta.profile[].
     *
     * @param array<string, mixed> $resource The FHIR resource
     *
     * @return array<string> Profile URLs
     */
    private function extractMetaProfiles(array $resource): array
    {
        if (!isset($resource['meta']['profile'])) {
            return [];
        }

        $profiles = $resource['meta']['profile'];

        // Ensure it's an array
        if (!is_array($profiles)) {
            return [];
        }

        // Filter out non-string values
        return array_filter($profiles, fn ($profile) => is_string($profile) && $profile !== '');
    }

    /**
     * Check if resource declares any profiles.
     *
     * @param array<string, mixed> $resource The FHIR resource
     */
    public function hasMetaProfiles(array $resource): bool
    {
        return !empty($this->extractMetaProfiles($resource));
    }

    /**
     * Get configured default profile for a resource type.
     *
     * @return array<string>|null
     */
    public function getDefaultProfilesForType(string $resourceType): ?array
    {
        if (!isset($this->defaultProfiles[$resourceType])) {
            return null;
        }

        $defaults = $this->defaultProfiles[$resourceType];

        return is_array($defaults) ? $defaults : [$defaults];
    }

    /**
     * Check if any profiles will be selected for a resource.
     *
     * @param array<string, mixed> $resource         The FHIR resource
     * @param array<string>|null   $explicitProfiles Explicitly requested profiles
     */
    public function hasProfiles(array $resource, ?array $explicitProfiles = null): bool
    {
        return !empty($this->resolve($resource, $explicitProfiles));
    }
}

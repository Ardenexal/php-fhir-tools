<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Terminology;

use Ardenexal\FHIRTools\Component\CodeGeneration\Loader\PackageLoader;

/**
 * Package-based terminology resolver using locally loaded FHIR packages.
 *
 * Fallback resolver that uses ValueSets loaded from FHIR packages
 * when remote terminology server is unavailable.
 *
 * @author Alex Murray <alex@ardenexal.com>
 */
final class PackageTerminologyResolver implements TerminologyResolverInterface
{
    public function __construct(
        private readonly PackageLoader $packageLoader,
    ) {
    }

    public function validateCode(
        string $valueSetUrl,
        string $system,
        string $code,
        ?string $display = null,
        ?string $version = null
    ): bool {
        $valueSet = $this->loadValueSet($valueSetUrl, $version);
        
        if ($valueSet === null) {
            return false;
        }

        // Check if ValueSet has an expansion
        if (isset($valueSet['expansion']['contains'])) {
            return $this->validateCodeInExpansion($valueSet['expansion']['contains'], $system, $code);
        }

        // Check compose rules
        if (isset($valueSet['compose']['include'])) {
            return $this->validateCodeInCompose($valueSet['compose']['include'], $system, $code);
        }

        return false;
    }

    public function expand(
        string $valueSetUrl,
        ?string $version = null,
        ?int $count = null,
        ?int $offset = null
    ): array {
        $valueSet = $this->loadValueSet($valueSetUrl, $version);
        
        if ($valueSet === null) {
            return [];
        }

        $codes = [];

        // Use pre-computed expansion if available
        if (isset($valueSet['expansion']['contains'])) {
            $codes = $this->extractCodesFromExpansion($valueSet['expansion']['contains']);
        } elseif (isset($valueSet['compose']['include'])) {
            // Perform simple expansion from compose rules
            $codes = $this->expandFromCompose($valueSet['compose']['include']);
        }

        // Apply pagination
        if ($offset !== null || $count !== null) {
            $start = $offset ?? 0;
            $length = $count ?? count($codes);
            return array_slice($codes, $start, $length);
        }

        return $codes;
    }

    public function canResolve(string $valueSetUrl): bool
    {
        return $this->loadValueSet($valueSetUrl) !== null;
    }

    /**
     * Load ValueSet from package loader.
     */
    private function loadValueSet(string $url, ?string $version = null): ?array
    {
        try {
            // Try to load from packages
            $resource = $this->packageLoader->loadResourceByCanonicalUrl($url, $version);
            
            if ($resource !== null && isset($resource['resourceType']) && $resource['resourceType'] === 'ValueSet') {
                return $resource;
            }
        } catch (\Exception $e) {
            // ValueSet not found in packages
        }

        return null;
    }

    /**
     * Validate code against expanded ValueSet.
     */
    private function validateCodeInExpansion(array $contains, string $system, string $code): bool
    {
        foreach ($contains as $concept) {
            if (($concept['system'] ?? '') === $system && ($concept['code'] ?? '') === $code) {
                return true;
            }
            
            // Check nested concepts
            if (isset($concept['contains'])) {
                if ($this->validateCodeInExpansion($concept['contains'], $system, $code)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Validate code against compose rules.
     */
    private function validateCodeInCompose(array $includes, string $system, string $code): bool
    {
        foreach ($includes as $include) {
            if (($include['system'] ?? '') !== $system) {
                continue;
            }

            // If no specific concepts listed, system inclusion means all codes valid
            if (!isset($include['concept'])) {
                // Check for filters - if present, we can't validate without expansion
                if (isset($include['filter'])) {
                    continue;
                }
                return true;
            }

            // Check specific concepts
            foreach ($include['concept'] as $concept) {
                if (($concept['code'] ?? '') === $code) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Extract codes from expansion.
     */
    private function extractCodesFromExpansion(array $contains): array
    {
        $codes = [];

        foreach ($contains as $concept) {
            $code = [
                'system' => $concept['system'] ?? '',
                'code' => $concept['code'] ?? '',
            ];
            
            if (isset($concept['display'])) {
                $code['display'] = $concept['display'];
            }
            
            $codes[] = $code;

            // Add nested concepts
            if (isset($concept['contains'])) {
                $codes = array_merge($codes, $this->extractCodesFromExpansion($concept['contains']));
            }
        }

        return $codes;
    }

    /**
     * Perform simple expansion from compose rules.
     *
     * Note: This is a simplified expansion that doesn't handle filters.
     * For complete expansion, use a terminology server.
     */
    private function expandFromCompose(array $includes): array
    {
        $codes = [];

        foreach ($includes as $include) {
            $system = $include['system'] ?? '';

            // Only expand if specific concepts are listed
            if (isset($include['concept'])) {
                foreach ($include['concept'] as $concept) {
                    $code = [
                        'system' => $system,
                        'code' => $concept['code'] ?? '',
                    ];
                    
                    if (isset($concept['display'])) {
                        $code['display'] = $concept['display'];
                    }
                    
                    $codes[] = $code;
                }
            }
        }

        return $codes;
    }
}

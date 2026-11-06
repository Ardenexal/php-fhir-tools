<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

/**
 * Searches several registries in order and returns the first match.
 *
 * A profile's parent chain usually crosses packages (an IG profile derives from a core profile),
 * so a typical setup is the IG's package directory first and the core package directory second.
 * Earlier registries win, which lets a caller override a published definition with a local one.
 *
 * @author Ardenexal
 */
final class CompositeStructureDefinitionRegistry implements StructureDefinitionRegistryInterface
{
    /**
     * @param list<StructureDefinitionRegistryInterface> $registries Registries in priority order
     */
    public function __construct(private readonly array $registries)
    {
    }

    public function find(string $fhirVersion, string $url, ?string $version = null): ?array
    {
        foreach ($this->registries as $registry) {
            $structureDefinition = $registry->find($fhirVersion, $url, $version);
            if ($structureDefinition !== null) {
                return $structureDefinition;
            }
        }

        return null;
    }

    public function fhirVersionsFor(string $url): array
    {
        $releases = [];
        foreach ($this->registries as $registry) {
            $releases = [...$releases, ...$registry->fhirVersionsFor($url)];
        }

        return array_values(array_unique($releases));
    }
}

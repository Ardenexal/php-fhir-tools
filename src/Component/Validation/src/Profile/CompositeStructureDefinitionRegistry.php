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
 * A registry that cannot read what it holds does not hide the others: the search goes on, and the
 * first such failure is rethrown only when no registry answers.
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

    public function find(string $url, string $fhirVersion, ?string $version = null): ?array
    {
        $failure = null;
        foreach ($this->registries as $registry) {
            try {
                $structureDefinition = $registry->find($url, $fhirVersion, $version);
            } catch (UnreadableStructureDefinitionException $e) {
                $failure ??= $e;

                continue;
            }
            if ($structureDefinition !== null) {
                return $structureDefinition;
            }
        }

        if ($failure !== null) {
            throw $failure;
        }

        return null;
    }

    public function fhirVersionsFor(string $url): array
    {
        $releases = [];
        $failure  = null;
        foreach ($this->registries as $registry) {
            try {
                $releases = [...$releases, ...$registry->fhirVersionsFor($url)];
            } catch (UnreadableStructureDefinitionException $e) {
                $failure ??= $e;
            }
        }

        if ($releases === [] && $failure !== null) {
            throw $failure;
        }

        return array_values(array_unique($releases));
    }
}

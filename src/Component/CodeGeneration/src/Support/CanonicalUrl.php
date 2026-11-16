<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Support;

/**
 * Helpers for FHIR canonical URLs.
 *
 * A canonical URL may carry a `|<version>` suffix (`.../StructureDefinition/Endpoint|4.0.1`).
 * Published IGs use the versioned form freely — in `baseDefinition`, `type.profile`,
 * `targetProfile` and `binding.valueSet` — while the definitions they point at are indexed
 * under the *bare* URL. Every lookup and every class-name derivation must therefore strip the
 * suffix first.
 *
 * Skipping the strip does not fail loudly, which is what makes it worth a named helper: the
 * index lookup simply misses, and the caller falls through to deriving a name from the raw
 * segment. `Endpoint|4.0.1` pascal-cases to `Endpoint401`, so the generator emitted
 * `…\Resource\Endpoint401Resource` — a class that cannot exist — into 73 of 108 generated
 * files. PHPStan then aborted the consuming project's whole analysis with a severe
 * "Class not found" error rather than reporting a normal finding.
 */
final class CanonicalUrl
{
    /**
     * Strip the `|<version>` suffix from a canonical URL, returning the bare URL.
     *
     * Returns the input unchanged when there is no suffix. Only the first `|` is honoured;
     * anything after it is version metadata, never part of the URL.
     */
    public static function stripVersion(string $url): string
    {
        $pipe = strpos($url, '|');

        return $pipe === false ? $url : substr($url, 0, $pipe);
    }
}

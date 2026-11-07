<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation\Profile;

/**
 * Picks the definition a canonical URL without a `|version` suffix refers to.
 *
 * @internal
 *
 * @author Ardenexal
 */
final class BusinessVersion
{
    /**
     * Return the highest business version, compared with `version_compare()`.
     *
     * An unversioned entry ('') sorts below any version. Ties keep the earlier entry.
     *
     * Callers pass array keys, and PHP turns a numeric-string key such as '1' into an int,
     * so each version is cast back to a string before it is compared.
     *
     * @param non-empty-list<int|string> $versions
     */
    public static function latest(array $versions): string
    {
        $latest = (string) $versions[0];
        foreach ($versions as $version) {
            $candidate = (string) $version;
            if (version_compare($candidate, $latest, '>')) {
                $latest = $candidate;
            }
        }

        return $latest;
    }
}

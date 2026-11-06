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
     * @param non-empty-list<string> $versions
     */
    public static function latest(array $versions): string
    {
        $latest = $versions[0];
        foreach ($versions as $candidate) {
            if (version_compare($candidate, $latest, '>')) {
                $latest = $candidate;
            }
        }

        return $latest;
    }
}

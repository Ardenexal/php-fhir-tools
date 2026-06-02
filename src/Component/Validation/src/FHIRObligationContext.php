<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRObligation;

/**
 * Scopes obligation validation to a specific actor.
 *
 * An obligation with no actor applies to all actors (returns true for any context).
 * An obligation with a specific actor only applies when actorUrl matches.
 * When actorUrl is null, only obligations without a specific actor apply.
 */
final class FHIRObligationContext
{
    public function __construct(
        public readonly ?string $actorUrl,
    ) {
    }

    public function matchesObligation(FHIRObligation $obligation): bool
    {
        if ($obligation->actor === null) {
            return true;
        }

        return $obligation->actor === $this->actorUrl;
    }
}

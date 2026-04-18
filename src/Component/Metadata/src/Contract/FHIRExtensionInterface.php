<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Contract;

/**
 * Common interface for all FHIR Extension classes across versions (R4, R4B, R5).
 *
 * Provides a version-agnostic typing point for extension objects so that
 * code operating on extensions does not need to import a specific generated
 * Extension class. Typed IG extension subclasses inherit this interface
 * automatically by extending the base Extension class.
 *
 * @author Ardenexal
 */
interface FHIRExtensionInterface
{
    /**
     * Returns the extension URL that identifies its meaning.
     *
     * Returns null when the url property has not been set, which should
     * only occur for partially-constructed objects.
     */
    public function getExtensionUrl(): ?string;
}

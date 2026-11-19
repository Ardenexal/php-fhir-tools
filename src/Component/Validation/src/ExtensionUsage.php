<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Where an extension may be used, read from its generated class or its runtime definition.
 *
 * @internal
 *
 * @author Ardenexal
 */
final readonly class ExtensionUsage
{
    /**
     * @param bool|null   $isModifier       Whether the extension belongs in `modifierExtension`; null when its class does not say
     * @param string|null $startFhirVersion The earliest FHIR version it may be used in; null for no limit
     * @param string|null $endFhirVersion   The latest FHIR version it may be used in; null for no limit
     */
    public function __construct(
        public ?bool $isModifier,
        public ?string $startFhirVersion,
        public ?string $endFhirVersion,
    ) {
    }
}

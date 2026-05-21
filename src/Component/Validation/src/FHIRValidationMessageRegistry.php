<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * In-memory registry for overriding validation message templates per constraint class.
 *
 * Keys are simple class names without namespace (e.g. 'FHIRFixedValue'), matching the
 * class names in this package which are globally unique. This keeps fhir.yaml config
 * readable without requiring fully-qualified class names.
 */
final class FHIRValidationMessageRegistry
{
    /** @var array<string, string> */
    private array $overrides = [];

    /** @param string $constraintKey Simple class name, e.g. 'FHIRFixedValue' */
    public function setOverride(string $constraintKey, string $messageTemplate): void
    {
        $this->overrides[$constraintKey] = $messageTemplate;
    }

    /** @param string $constraintKey Simple class name, e.g. 'FHIRFixedValue' */
    public function getOverride(string $constraintKey): ?string
    {
        return $this->overrides[$constraintKey] ?? null;
    }
}

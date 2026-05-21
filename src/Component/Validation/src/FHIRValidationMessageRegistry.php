<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * In-memory registry for overriding validation message templates per constraint class.
 *
 * Consumers can override the default violation message for any constraint class before
 * validation runs. Validators in M02+ inject this registry and call getOverride() to
 * substitute a custom message when one is registered.
 */
final class FHIRValidationMessageRegistry
{
    /** @var array<class-string, string> */
    private array $overrides = [];

    /** @param class-string $constraintClass */
    public function setOverride(string $constraintClass, string $messageTemplate): void
    {
        $this->overrides[$constraintClass] = $messageTemplate;
    }

    /** @param class-string $constraintClass */
    public function getOverride(string $constraintClass): ?string
    {
        return $this->overrides[$constraintClass] ?? null;
    }
}

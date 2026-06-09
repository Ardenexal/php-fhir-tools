<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Pre-loaded in-memory terminology client for offline and test use.
 * Entries not in the map default to $defaultResult (true = allow unknown, false = deny unknown).
 *
 * @param array<string, array<string, bool>> $map valueSetUrl => ['system|code' => bool]
 */
final class InMemoryFHIRTerminologyClient implements FHIRTerminologyClientInterface
{
    /**
     * @param array<string, array<string, bool>> $map valueSetUrl => ['system|code' => bool]
     */
    public function __construct(
        private readonly array $map,
        private readonly bool $defaultResult = true,
    ) {
    }

    public function validateCode(string $valueSetUrl, mixed $value): bool
    {
        if ($value instanceof \BackedEnum) {
            $code = (string) $value->value;
        } elseif (is_string($value) || is_int($value)) {
            $code = (string) $value;
        } else {
            return $this->defaultResult;
        }

        return $this->lookup($valueSetUrl, '|' . $code);
    }

    public function validateCoding(string $valueSetUrl, string $system, string $code): bool
    {
        return $this->lookup($valueSetUrl, $system . '|' . $code);
    }

    private function lookup(string $valueSetUrl, string $key): bool
    {
        return $this->map[$valueSetUrl][$key] ?? $this->defaultResult;
    }
}

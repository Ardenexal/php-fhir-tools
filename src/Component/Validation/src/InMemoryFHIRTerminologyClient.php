<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

/**
 * Pre-loaded in-memory terminology client for offline and test use.
 * Entries not in the map default to $defaultResult (true = allow unknown, false = deny unknown).
 *
 * @param array<string, array<string, bool>>   $map        valueSetUrl => ['system|code' => bool]
 * @param array<string, array<string, string>> $displayMap valueSetUrl => ['system|code' => correct_display]
 */
final class InMemoryFHIRTerminologyClient implements FHIRTerminologyClientInterface
{
    /**
     * @param array<string, array<string, bool>>   $map        valueSetUrl => ['system|code' => bool]
     * @param array<string, array<string, string>> $displayMap valueSetUrl => ['system|code' => correct_display]
     */
    public function __construct(
        private readonly array $map,
        private readonly bool $defaultResult = true,
        private readonly array $displayMap = [],
    ) {
    }

    /**
     * Returns true when $value is a valid member of the named value set.
     *
     * Looks up the value in the preloaded map using the key format '|code'. Returns
     * $defaultResult when the value set URL or code is not present in the map.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param mixed  $value       The code to validate; accepts string, int, or BackedEnum
     *
     * @return bool True when the code is a valid member, false otherwise
     */
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

    /**
     * Returns true when the system+code pair is a valid member of the named value set.
     *
     * Looks up the pair using the key format 'system|code'. Returns $defaultResult when
     * the value set URL or system+code key is not present in the map.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      The coding system URI
     * @param string $code        The code within that system
     *
     * @return bool True when the coding is a valid member, false otherwise
     */
    public function validateCoding(string $valueSetUrl, string $system, string $code): bool
    {
        return $this->lookup($valueSetUrl, $system . '|' . $code);
    }

    /**
     * Validates the system+code pair and checks whether the provided display matches the canonical one.
     *
     * Validity is determined by the same map lookup as validateCoding(). The correct display is
     * read from $displayMap using the same 'system|code' key; if no entry exists the display is
     * assumed correct and correctDisplay is null.
     *
     * @param string $valueSetUrl Canonical URL of the value set to check against
     * @param string $system      The coding system URI
     * @param string $code        The code within that system
     * @param string $display     The display string provided by the caller
     *
     * @return CodingValidationResult Validity flag and optional corrected display string
     */
    public function validateCodingWithDisplay(
        string $valueSetUrl,
        string $system,
        string $code,
        string $display,
    ): CodingValidationResult {
        $key            = $system . '|' . $code;
        $valid          = $this->lookup($valueSetUrl, $key);
        $correctDisplay = $this->displayMap[$valueSetUrl][$key] ?? null;

        if ($correctDisplay !== null && $correctDisplay === $display) {
            $correctDisplay = null;
        }

        return new CodingValidationResult($valid, $correctDisplay);
    }

    private function lookup(string $valueSetUrl, string $key): bool
    {
        return $this->map[$valueSetUrl][$key] ?? $this->defaultResult;
    }
}

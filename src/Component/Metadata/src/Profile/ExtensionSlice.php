<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Profile;

/**
 * One `Extension.extension` slice of a complex extension: a sub-extension identified by its relative url.
 *
 * @author Ardenexal
 */
final readonly class ExtensionSlice
{
    /**
     * @param string      $sliceName The slice name, which is also the sub-extension's url
     * @param int         $min       Minimum occurrences
     * @param string|null $max       Maximum occurrences: a number or '*'; null when the definition does not say
     * @param string|null $valueType The first type code its `value[x]` allows, or null when it names none
     */
    public function __construct(
        public string $sliceName,
        public int $min,
        public ?string $max,
        public ?string $valueType,
    ) {
    }

    /**
     * Whether a generated class requires this slice: at least one, and at most one.
     *
     * A required slice that may repeat becomes a list parameter, which the generated class does
     * not check for emptiness, so it is not required here either.
     */
    public function isRequired(): bool
    {
        return $this->min >= 1 && !$this->repeats();
    }

    /**
     * Whether the generated class takes this slice as a list; one with no stated maximum is single.
     */
    public function repeats(): bool
    {
        return !in_array($this->max ?? '1', ['0', '1'], true);
    }
}

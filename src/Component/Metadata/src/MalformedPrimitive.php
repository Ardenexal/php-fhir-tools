<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata;

/**
 * One `boolean` or `integer` element whose text the deserializer could not read as its type.
 *
 * Those two types are generated as bare PHP `bool` and `int` properties, so the model cannot hold
 * the text as written: `TRUE`, `1` and `yes` all reach a `bool` property as some boolean, and
 * `34534536346345345345` reaches an `int` property saturated at `PHP_INT_MAX`. The reader still
 * coerces the value exactly as before; this record is what keeps the original text for validation.
 */
final readonly class MalformedPrimitive
{
    /** A `boolean` whose text is neither `true` nor `false`. */
    public const KIND_BOOLEAN = 'boolean';

    /** An `integer` (or `positiveInt`/`unsignedInt`) whose text is not a 32-bit integer. */
    public const KIND_INTEGER = 'integer';

    /** An element present with neither a value nor any extension, e.g. `<valueInteger/>`. */
    public const KIND_NO_VALUE = 'no-value';

    /**
     * @param string      $propertyName the model property the value was written to
     * @param string      $kind         one of the self::KIND_* constants
     * @param string|null $lexeme       the text as written; null for self::KIND_NO_VALUE. A JSON
     *                                  value is re-encoded as JSON, so `1` stays `1` and the string
     *                                  `"true"` keeps its quotes
     */
    public function __construct(
        public string $propertyName,
        public string $kind,
        public ?string $lexeme,
    ) {
    }
}

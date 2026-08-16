<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Contract;

/**
 * Contract for FHIR temporal primitive value objects.
 * Implementations: FHIRDate, FHIRDateTime, FHIRTime, FHIRInstant.
 */
interface FHIRTemporalValue extends \Stringable
{
    /**
     * Parse a raw FHIR temporal string into the value object.
     *
     * @throws \Throwable on invalid input
     */
    public static function parse(string $raw): static;

    /**
     * Build a value object that retains a lexeme {@see parse()} could not read.
     *
     * FHIR primitive syntax is a *validation* rule, not a parsing precondition: the reference
     * validator reads the whole document, keeps every lexeme as written, and reports one located
     * error per malformed primitive. Aborting the deserialization instead loses the other findings
     * in the document, so the deserializer records the failure here and lets validation report it.
     *
     * `__toString()` returns the original lexeme, so a fail-soft value round-trips as written
     * rather than silently becoming empty.
     *
     * @param string $raw   the lexeme exactly as it appeared in the document
     * @param string $error the parser's own diagnostic, for callers that want the underlying cause
     */
    public static function unparsed(string $raw, string $error): static;

    /**
     * The parser diagnostic when this value could not be read, or null when it parsed cleanly.
     *
     * Callers that need a parsed value must check this first: {@see self::getValue()} is null
     * exactly when this is non-null.
     */
    public function getParseError(): ?string;
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Exception;

/**
 * The document was read and understood, then rejected for a stated FHIR conformance rule.
 *
 * This is NOT "we could not parse the input". The bytes were valid, the element was located, and the
 * value breaks a rule the spec states — `Composition.subject` is `0..1` and the document supplies two,
 * or a `0..*` element arrives as a JSON object instead of an array. **That is a validation finding**,
 * equivalent to a `FHIRValidationViolation`; it merely has to be raised during deserialization because
 * the generated model cannot physically hold the value long enough for validation to run.
 *
 * The distinction is load-bearing for the conformance oracle. A case that cannot be parsed contributes
 * no finding of ours, so it counts 0 and is reported as `UNREAD`. A case rejected here contributes
 * exactly one finding, so it belongs in the comparison set with `errorCount: 1`. Conflating them makes
 * a correct, Java-matching finding read as a `BELOW` gap — which is precisely what happened to
 * `bundle-dual-subject`, where our message matches the reference validator's verbatim.
 *
 * Only throw this where the message is **self-constructed to state the rule**. The other
 * `FHIRSerializationException::formatError()` call sites re-wrap an upstream `$e->getMessage()` and are
 * genuinely opaque — those must stay unread.
 *
 * Extends FHIRSerializationException so every existing `catch` keeps working and the message text is
 * unchanged; only code that wants the distinction needs to know this type exists.
 */
final class FHIRConformanceViolationException extends FHIRSerializationException
{
    private function __construct(
        string $message,
        /** The rule text without the "Format error (…)" prefix, for reporting as a violation. */
        public readonly string $finding,
    ) {
        parent::__construct(message: $message);
    }

    /**
     * @param string $format  'xml' or 'json', for message parity with formatError()
     * @param string $finding the rule that was broken, worded to mirror the HL7 Java validator
     */
    public static function inFormat(string $format, string $finding): self
    {
        return new self(sprintf('Format error (%s): %s', $format, $finding), $finding);
    }
}

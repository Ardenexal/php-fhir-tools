<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Exception;

/**
 * The bytes are not a readable JSON or XML document.
 *
 * This is the counterpart to {@see FHIRConformanceViolationException}, and the two must not be
 * conflated. There, the document was read and then rejected on a stated FHIR rule. Here, no document
 * was ever recovered: the JSON has a stray comma, the XML has a mismatched tag, the payload is not
 * UTF-8, or it begins with neither `{`, `[` nor `<`.
 *
 * **That is still a finding, not an absence of one.** The HL7 Java reference validator reports an
 * `OperationOutcome` error for every case in this class — `Unable to parse JSON`, `Content is not
 * allowed in prolog`, `The element type "id" must be terminated…` — rather than refusing to answer.
 * Counting ours as zero and filing the case under `UNREAD` hid it from the comparison set entirely:
 * not `ABOVE`, not `BELOW`, invisible to any regression check.
 *
 * ## What must NOT be thrown as this
 *
 * The discriminator is "is the defect in the document, or in us?", and it is a *type*, not a phrase —
 * message-matching was rejected when the same problem was solved for conformance violations.
 *
 *  - `Unable to detect target class from data` — the document parsed. We simply cannot map it to a
 *    generated model. `R5.logicalxml-nonamespace` is the proof this matters: Java reads it and reports
 *    **zero** errors, so reporting one would put us `ABOVE` the reference validator — the one outcome
 *    the conformance gate forbids.
 *  - `no supporting normalizer found` and the `Format error (…)` normalizer failures — likewise our
 *    limitation, not the document's defect.
 *
 * Both keep throwing the plain {@see FHIRSerializationException} and stay `UNREAD`.
 *
 * Extends FHIRSerializationException so every existing `catch` keeps working and no message changes;
 * only code that wants the distinction needs to know this type exists.
 */
final class FHIRUnreadableDocumentException extends FHIRSerializationException
{
    private function __construct(
        string $message,
        /** The reason without any wrapping prefix, for reporting as a violation at the document root. */
        public readonly string $finding,
    ) {
        parent::__construct(message: $message);
    }

    /**
     * @param string $finding why the bytes could not be read, worded to mirror the HL7 Java validator
     */
    public static function because(string $finding): self
    {
        return new self($finding, $finding);
    }
}

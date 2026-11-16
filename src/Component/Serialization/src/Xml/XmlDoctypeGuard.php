<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Xml;

use Ardenexal\FHIRTools\Component\Serialization\Exception\FHIRSerializationException;

/**
 * Rejects any XML document carrying a DOCTYPE declaration, before it reaches a parser.
 *
 * FHIR XML has no legitimate use for a DOCTYPE, and the HL7 Java reference validator refuses one
 * outright. We previously relied on two weaker guards, and neither actually prevented resolution:
 *
 *  - `LIBXML_NONET` blocks *network* retrieval but not local `file://` entities, and
 *    `LIBXML_NOENT` being off stops entity *substitution* — not the resolution attempt itself;
 *  - `XmlEncoder::DECODER_IGNORED_NODE_TYPES` discards the DOCTYPE *node*, which happens only
 *    after libxml has already parsed the declaration.
 *
 * Measured on the corpus before this guard existed, `list-xhtml-xxe1.xml` failed with
 * `Invalid URI: file://c:\temp\xxe.txt` — libxml had resolved the external entity's system
 * identifier and objected only to the URI being malformed. A well-formed path would have been
 * attempted. The refusal was accidental, not designed.
 *
 * Detection is confined to the prolog, where a DOCTYPE is the only place one may legally appear.
 * Scanning the whole document would reject any instance whose narrative text merely contains the
 * literal `<!DOCTYPE` — valid FHIR that we must keep accepting.
 *
 * **The prolog walk is a byte comparison, so it only sees a UTF-8 document.** In UTF-16 every ASCII
 * character is a byte pair, so `<!DOCTYPE` never matches and the walk reports "no DOCTYPE" on a
 * document that plainly has one — libxml then parses it, declaration and all. `assertUtf8()` closes
 * that hole by refusing the encodings this scan cannot read, and callers must run both.
 */
final class XmlDoctypeGuard
{
    private const BOM = "\xEF\xBB\xBF";

    /**
     * Byte order marks for the encodings the prolog walk cannot read.
     *
     * A UTF-32 BOM shares its first two bytes with a UTF-16 one, so this list is only ever used to
     * answer "is this one of them", never to name which.
     */
    private const NON_UTF8_BOMS = [
        "\x00\x00\xFE\xFF", // UTF-32BE
        "\xFF\xFE\x00\x00", // UTF-32LE
        "\xFE\xFF",         // UTF-16BE
        "\xFF\xFE",         // UTF-16LE
    ];

    /**
     * How far in to look for the NUL byte that betrays a BOM-less UTF-16/32 payload.
     *
     * A UTF-16 document pairs its very first character with a NUL, so the byte always lands within
     * the first few bytes; the window is generous only so the check reads as "the start of the
     * document" rather than a magic pair of bytes.
     */
    private const ENCODING_SNIFF_BYTES = 4096;

    /**
     * @throws FHIRSerializationException if the document declares a DOCTYPE
     */
    public static function assertNoDoctype(string $xmlData): void
    {
        if (!self::declaresDoctype($xmlData)) {
            return;
        }

        throw new FHIRSerializationException('XML DOCTYPE declarations are not accepted: they permit external entity references (XXE). Remove the DOCTYPE and resubmit the document.');
    }

    /**
     * Refuse a payload that is not UTF-8, before anything tries to read it as text.
     *
     * FHIR mandates UTF-8, and the auto-detecting `deserialize()` path already refuses anything else
     * — `detectFormat()` sees byte pairs rather than `<` and reports "Unable to detect data format".
     * `deserializeFromXml()` skips that detection, so without this the two public entry points
     * disagree about which encodings they accept, and the stricter one is not the one handed a raw
     * XML string.
     *
     * The payload is rejected rather than transcoded. Re-encoding here would make the direct XML
     * entry point *more* permissive than the auto-detecting one, and would hand the rest of the
     * pipeline — the UTF-8-only BOM strip, the namespace resolver's byte-wise fast path — bytes none
     * of it was written for.
     *
     * @throws FHIRSerializationException if the document is not UTF-8
     */
    public static function assertUtf8(string $xmlData): void
    {
        if (self::isUtf8($xmlData)) {
            return;
        }

        throw new FHIRSerializationException('XML payloads must be UTF-8 encoded: this document is UTF-16 or UTF-32. FHIR mandates UTF-8. Re-encode the document and resubmit it.');
    }

    /**
     * Whether the payload can be read as UTF-8 text.
     *
     * Two signals, because a UTF-16/32 document need not carry a BOM: the byte order marks
     * themselves, and a NUL byte near the start. NUL is not a legal XML character in any encoding,
     * so its presence in UTF-8-interpreted bytes means the document is not UTF-8 — there is no
     * well-formed UTF-8 XML for this to reject.
     */
    private static function isUtf8(string $xmlData): bool
    {
        foreach (self::NON_UTF8_BOMS as $bom) {
            if (str_starts_with($xmlData, $bom)) {
                return false;
            }
        }

        return !str_contains(substr($xmlData, 0, self::ENCODING_SNIFF_BYTES), "\x00");
    }

    /**
     * Whether a DOCTYPE appears in the document prolog.
     *
     * Walks the prolog the way a parser does — skipping an optional BOM, whitespace, processing
     * instructions (which includes the XML declaration) and comments — and reports whether the next
     * markup construct opens a DOCTYPE. Anything else means the root element has started and no
     * DOCTYPE can follow.
     */
    private static function declaresDoctype(string $xmlData): bool
    {
        $offset = str_starts_with($xmlData, self::BOM) ? strlen(self::BOM) : 0;
        $length = strlen($xmlData);

        while ($offset < $length) {
            // Whitespace between prolog constructs.
            if (preg_match('/\G\s+/', $xmlData, $m, 0, $offset) === 1) {
                $offset += strlen($m[0]);
                continue;
            }

            if (substr($xmlData, $offset, 9) === '<!DOCTYPE') {
                return true;
            }

            // Processing instruction, which includes the XML declaration.
            if (substr($xmlData, $offset, 2) === '<?') {
                $end = strpos($xmlData, '?>', $offset);
                if ($end === false) {
                    return false;
                }
                $offset = $end + 2;
                continue;
            }

            // Comment: `<!--...-->`.
            if (substr($xmlData, $offset, 4) === '<!--') {
                $end = strpos($xmlData, '-->', $offset);
                if ($end === false) {
                    return false;
                }
                $offset = $end + 3;
                continue;
            }

            // Anything else is the root element (or malformed input, which the parser reports
            // far better than we could). Either way the prolog is over.
            return false;
        }

        return false;
    }
}

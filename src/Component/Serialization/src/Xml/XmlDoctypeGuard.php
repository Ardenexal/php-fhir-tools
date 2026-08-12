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
 */
final class XmlDoctypeGuard
{
    private const BOM = "\xEF\xBB\xBF";

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

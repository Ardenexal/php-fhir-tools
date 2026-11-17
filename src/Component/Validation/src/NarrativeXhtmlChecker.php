<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Checks every `xhtml`-typed value in a resource tree (in practice `Narrative.div`) and reports
 * what is wrong with it, located on the offending element.
 *
 * ## Why this is not a Symfony constraint
 *
 * FHIR expresses these rules as the Narrative invariants txt-1 and txt-2, whose FHIRPath is
 * `htmlChecks()`. Our engine's FHIRPath HtmlChecksFunction
 * returns a bare boolean, so an invariant built on it can only say "the narrative is wrong"
 * — never which element, which attribute, or why. The Java reference validator reports the
 * diagnostic *and* the invariant, so a boolean can never reach parity. This pass walks the tree
 * itself and emits both, which also keeps it out of the generated models.
 *
 * ## Rule order matters
 *
 * A DOCTYPE short-circuits (txt-1 plus the XXE diagnostic, nothing else). Undefined entities are
 * reported on their own — with no txt-1 and no txt-2 — and substituted out before parsing, so that
 * an entity can never masquerade as malformedness. Only then is the document parsed; a genuine
 * parse failure is the sole trigger for txt-2. txt-1 is emitted at most once per narrative, never
 * once per diagnostic.
 *
 * ## Namespaces
 *
 * A correct narrative can arrive with no namespace at all: the XML reader leaves the XHTML namespace
 * off a narrative that is wholly in it, so absence is not evidence. Only a namespace the markup carries is
 * judged ({@see firstWrongNamespace()}). Prefixed names are still skipped, because a prefix binding
 * declared outside the narrative string cannot be checked from inside it.
 *
 * A value not starting with markup is left alone rather than reported as unparsable: it cannot be
 * judged as XHTML, and a model built in code can hold a text-only narrative that way.
 */
final class NarrativeXhtmlChecker
{
    /** @var array<class-string, array<string, bool>> {@see isXhtmlProperty()} answers, by class and property */
    private array $xhtmlProperties = [];

    public function __construct(
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    private const string XMLNS_NS = 'http://www.w3.org/2000/xmlns/';

    private const string XHTML_NS = 'http://www.w3.org/1999/xhtml';

    /**
     * URI schemes that name a thing rather than locate it, so a browser cannot follow them.
     *
     * Kept to the identifier schemes FHIR itself uses (`urn:`, and the bare `oid:` and `uuid:` forms)
     * rather than an allow list of web schemes: an allow list would also reject legitimate schemes the reference validator
     * accepts, and over-reporting is the direction the conformance comparison must never move.
     */
    private const array NON_HYPERLINKABLE_SCHEMES = ['urn' => true, 'oid' => true, 'uuid' => true];

    /** One character outside RFC 3986's URL alphabet (letters, digits, `-._~`, delimiters and `%`). */
    private const string URL_INVALID_CHARACTER = '/[^\p{L}\p{N}\-._~:\/?#\[\]@!$&\'()*+,;=%]/u';

    private const string TXT_1 = "Constraint failed: txt-1: 'The narrative SHALL contain only the basic html formatting elements and attributes described in chapters 7-11 (except section 4 of chapter 9) and 15 of the HTML 4.0 standard, <a> elements (either name or href), images and internally contained style attributes'";

    private const string TXT_2 = "Constraint failed: txt-2: 'The narrative SHALL have some non-whitespace content'";

    /** R5 outcomes name the defining StructureDefinition on a constraint failure; R4 and R4B do not. */
    private const string DEFINED_IN = ' (defined in http://hl7.org/fhir/StructureDefinition/Narrative)';

    /** Entities XML defines itself; anything else in a narrative is undefined without a DOCTYPE. */
    private const array PREDEFINED_ENTITIES = ['amp' => true, 'lt' => true, 'gt' => true, 'quot' => true, 'apos' => true];

    /** The HTML 4.0 elements FHIR permits in a narrative (chapters 7-11 except 9.4, and 15). */
    private const array ALLOWED_ELEMENTS = [
        'a'    => true, 'abbr' => true, 'acronym' => true, 'address' => true, 'b' => true, 'bdo' => true,
        'big'  => true, 'blockquote' => true, 'br' => true, 'caption' => true, 'cite' => true,
        'code' => true, 'col' => true, 'colgroup' => true, 'dd' => true, 'dfn' => true, 'div' => true,
        'dl'   => true, 'dt' => true, 'em' => true, 'h1' => true, 'h2' => true, 'h3' => true,
        'h4'   => true, 'h5' => true, 'h6' => true, 'hr' => true, 'i' => true, 'img' => true,
        'kbd'  => true, 'li' => true, 'map' => true, 'area' => true, 'ol' => true, 'p' => true,
        'pre'  => true, 'q' => true, 'samp' => true, 'small' => true, 'span' => true, 'strong' => true,
        'sub'  => true, 'sup' => true, 'table' => true, 'tbody' => true, 'td' => true, 'tfoot' => true,
        'th'   => true, 'thead' => true, 'tr' => true, 'tt' => true, 'ul' => true, 'var' => true,
    ];

    /** Attributes valid on any permitted element (HTML 4.0 core, i18n and the XML attributes). */
    private const array GLOBAL_ATTRIBUTES = [
        'id'       => true, 'class' => true, 'style' => true, 'title' => true, 'lang' => true,
        'xml:lang' => true, 'xml:space' => true, 'dir' => true, 'accesskey' => true,
        'tabindex' => true,
    ];

    /** Attributes valid only on the element that keys them. */
    private const array ELEMENT_ATTRIBUTES = [
        'a'          => ['href' => true, 'name' => true, 'rel' => true, 'rev' => true, 'type' => true, 'charset' => true, 'hreflang' => true, 'shape' => true, 'coords' => true],
        'area'       => ['shape' => true, 'coords' => true, 'href' => true, 'nohref' => true, 'alt' => true],
        'blockquote' => ['cite' => true],
        'br'         => ['clear' => true],
        'col'        => ['span' => true, 'width' => true, 'align' => true, 'valign' => true, 'char' => true, 'charoff' => true],
        'colgroup'   => ['span' => true, 'width' => true, 'align' => true, 'valign' => true, 'char' => true, 'charoff' => true],
        'div'        => ['align' => true],
        'dl'         => ['compact' => true],
        'h1'         => ['align' => true], 'h2' => ['align' => true], 'h3' => ['align' => true],
        'h4'         => ['align' => true], 'h5' => ['align' => true], 'h6' => ['align' => true],
        'hr'         => ['align' => true, 'noshade' => true, 'size' => true, 'width' => true],
        'img'        => ['src' => true, 'alt' => true, 'longdesc' => true, 'height' => true, 'width' => true, 'usemap' => true, 'ismap' => true, 'align' => true, 'border' => true, 'hspace' => true, 'vspace' => true],
        'li'         => ['type' => true, 'value' => true],
        'map'        => ['name' => true],
        'ol'         => ['type' => true, 'start' => true, 'compact' => true],
        'p'          => ['align' => true],
        'pre'        => ['width' => true],
        'q'          => ['cite' => true],
        'table'      => ['summary' => true, 'width' => true, 'border' => true, 'frame' => true, 'rules' => true, 'cellspacing' => true, 'cellpadding' => true, 'align' => true, 'bgcolor' => true],
        'tbody'      => ['align' => true, 'valign' => true, 'char' => true, 'charoff' => true],
        'td'         => ['abbr' => true, 'axis' => true, 'headers' => true, 'scope' => true, 'rowspan' => true, 'colspan' => true, 'align' => true, 'valign' => true, 'char' => true, 'charoff' => true, 'nowrap' => true, 'width' => true, 'height' => true, 'bgcolor' => true],
        'tfoot'      => ['align' => true, 'valign' => true, 'char' => true, 'charoff' => true],
        'th'         => ['abbr' => true, 'axis' => true, 'headers' => true, 'scope' => true, 'rowspan' => true, 'colspan' => true, 'align' => true, 'valign' => true, 'char' => true, 'charoff' => true, 'nowrap' => true, 'width' => true, 'height' => true, 'bgcolor' => true],
        'thead'      => ['align' => true, 'valign' => true, 'char' => true, 'charoff' => true],
        'tr'         => ['align' => true, 'valign' => true, 'char' => true, 'charoff' => true, 'bgcolor' => true],
        'ul'         => ['type' => true, 'compact' => true],
    ];

    /** Block-level elements, which HTML 4.0 forbids inside a paragraph. */
    private const array BLOCK_ELEMENTS = [
        'p'          => true, 'div' => true, 'h1' => true, 'h2' => true, 'h3' => true, 'h4' => true,
        'h5'         => true, 'h6' => true, 'table' => true, 'ul' => true, 'ol' => true, 'dl' => true,
        'blockquote' => true, 'pre' => true, 'hr' => true, 'address' => true,
    ];

    /**
     * Walk a resource and report every problem found in its narratives.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        $visited = [];
        $links   = new NarrativeLinkTargets();

        $violations = $this->walk($resource, '', $visited, $links);

        foreach ($links->unresolved() as $link) {
            $violations[] = $this->violation(
                $link['path'],
                sprintf("Hyperlink '%s' at '%s' for '%s' does not resolve", $link['href'], $link['xpath'], $link['text']),
                null,
            );
        }

        return $violations;
    }

    /**
     * @param array<int, true>     $visited spl_object_id keys of already-visited objects (cycle guard)
     * @param NarrativeLinkTargets $links   the ids seen so far and the `#fragment` links still to resolve
     *
     * @return list<FHIRValidationViolation>
     */
    private function walk(object $node, string $path, array &$visited, NarrativeLinkTargets $links): array
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return [];
        }

        $visited[$id] = true;
        $violations   = [];

        // Logical models (CDA) are not walked. Their narrative is a StrucDoc markup tree, not
        // XHTML: it carries several top-level nodes rather than one wrapping div, and its vocabulary
        // (paragraph, content, linkHtml, styleCode) does not overlap the HTML 4.0 subset txt-1
        // enforces. Judging one by the other's rules reported three errors on a valid CDA section —
        // a spurious "malformed XHTML" from the extra top-level nodes, txt-1 for `paragraph`, and
        // txt-2 claiming there was no content at all. CDA narrative conformance is a separate
        // concern and is not implemented here.
        if ($this->attributes->classAttributes($node, LogicalModel::class) !== []) {
            return [];
        }

        foreach ($this->models->initializedPublicValues($node) as $name => $value) {
            $subPath = $path === '' ? $name : $path . '.' . $name;

            // An element or resource id is a target a `#fragment` link may name.
            if ($name === 'id') {
                $targetId = $this->readString($value);
                if ($targetId !== null) {
                    $links->addTarget($targetId);
                }
            }

            if ($this->isXhtmlProperty($node, $name)) {
                $xhtml = $this->readXhtml($value);
                if ($xhtml !== null) {
                    foreach ($this->checkXhtml($xhtml, $subPath, $this->fhirVersionOf($node), $links) as $v) {
                        $violations[] = $v;
                    }
                }

                continue;
            }

            if (is_object($value)) {
                foreach ($this->walk($value, $subPath, $visited, $links) as $v) {
                    $violations[] = $v;
                }
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        foreach ($this->walk($item, $subPath . '[' . $i . ']', $visited, $links) as $v) {
                            $violations[] = $v;
                        }
                    }
                }
            }
        }

        return $violations;
    }

    /**
     * Apply the narrative rules to one xhtml value.
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkXhtml(string $xhtml, string $path, string $fhirVersion, NarrativeLinkTargets $links): array
    {
        $trimmed = ltrim($xhtml);

        // Not markup: the XML pipeline strips the <div> wrapper off a text-only narrative, so a
        // bare string is an artefact of deserialization rather than evidence of a malformed
        // document. Same "looks like markup" test the XML normalizer uses.
        if ($trimmed === '' || !str_starts_with($trimmed, '<')) {
            return [];
        }

        $suffix = $fhirVersion === 'R5' ? self::DEFINED_IN : '';

        // A DOCTYPE is refused before parsing, so no entity it declares is ever expanded.
        if (preg_match('/<!DOCTYPE/i', $xhtml) === 1) {
            return [
                $this->violation($path, self::TXT_1 . $suffix, 'txt-1'),
                $this->violation($path, 'Malformed XHTML: Found a DocType declaration, and these are not allowed (XXE security vulnerability protection)', null),
            ];
        }

        $violations = [];
        $parsable   = $xhtml;

        foreach ($this->undefinedEntities($xhtml) as $entity) {
            $violations[] = $this->violation($path, sprintf("Invalid entity in the XHTML ('&%s;')", $entity), null);
            $parsable     = str_replace('&' . $entity . ';', '', $parsable);
        }

        $previous = libxml_use_internal_errors(true);
        $document = new \DOMDocument();
        $parsed   = $document->loadXML($parsable);
        $errors   = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->documentElement;

        if ($parsed === false || $root === null) {
            $violations[] = $this->violation($path, 'Error parsing XHTML: Malformed XHTML: ' . $this->describeParseError($errors), null);
            $violations[] = $this->violation($path, self::TXT_1 . $suffix, 'txt-1');
            $violations[] = $this->violation($path, self::TXT_2 . $suffix, 'txt-2');

            return $violations;
        }

        $wrongNamespace = $this->firstWrongNamespace($document);
        if ($wrongNamespace !== null) {
            $violations[] = $this->violation(
                $path,
                sprintf("Wrong namespace on the XHTML ('%s', should be '%s')", $wrongNamespace, self::XHTML_NS),
                null,
            );
        }

        $diagnostics  = [];
        $breachesTxt1 = false;

        foreach ($document->getElementsByTagName('*') as $element) {
            // A prefixed name is a namespace question, and the binding that would answer it is
            // already gone by the time validation runs. Left to the serialization layer.
            if (str_contains($element->nodeName, ':')) {
                continue;
            }

            $name    = (string) $element->localName;
            $local   = strtolower($name);
            $allowed = isset(self::ALLOWED_ELEMENTS[$local]);

            // Any element's id, and an anchor's name, can be the target of a `#fragment` link.
            foreach (['id', 'name'] as $targetAttribute) {
                if ($element->hasAttribute($targetAttribute)) {
                    $links->addTarget($element->getAttribute($targetAttribute));
                }
            }

            if (!$allowed) {
                $diagnostics[] = $this->violation($path, sprintf("Invalid element name in the XHTML ('%s')", $name), null);
                $breachesTxt1  = true;
            }

            foreach ($element->attributes ?? [] as $attribute) {
                if ($this->isNamespaceDeclaration($attribute)) {
                    continue;
                }

                if ($allowed && $this->isAllowedAttribute($local, strtolower($attribute->nodeName))) {
                    if ($local === 'a' && strtolower($attribute->nodeName) === 'href') {
                        foreach ($this->checkHref($attribute->value, $path, $element, $links) as $hrefViolation) {
                            $diagnostics[] = $hrefViolation;
                        }
                    }

                    continue;
                }

                $diagnostics[] = $this->violation($path, sprintf("Invalid attribute name in the XHTML ('%s' on '%s')", $attribute->nodeName, $name), null);
                $breachesTxt1  = true;
            }

            if ($allowed && isset(self::BLOCK_ELEMENTS[$local]) && $this->hasParagraphAncestor($element)) {
                $diagnostics[] = $this->violation($path, sprintf("Invalid element name inside a paragraph in the XHTML ('%s')", $name), null);
            }
        }

        if ($breachesTxt1) {
            $violations[] = $this->violation($path, self::TXT_1 . $suffix, 'txt-1');
        }

        // The reference validator reports a repeated finding once per narrative: two `<tr idref>`
        // rows, or the same `urn:` link written twice, are one message each.
        $seen = [];
        foreach ($diagnostics as $diagnostic) {
            if (isset($seen[$diagnostic->message])) {
                continue;
            }

            $seen[$diagnostic->message] = true;
            $violations[]               = $diagnostic;
        }

        return $violations;
    }

    /**
     * The first namespace in the narrative that is not XHTML's, or null when there is none.
     *
     * Only a namespace the markup itself carries is judged. A JSON narrative keeps its `xmlns`
     * verbatim, and the XML reader writes a namespace only where an element leaves its parent's,
     * including the FHIR namespace a `div` inherits when it declares none. Markup with no namespace at all is a
     * correct narrative whose namespace was dropped, so it is not evidence of anything.
     */
    private function firstWrongNamespace(\DOMDocument $document): ?string
    {
        foreach ($document->getElementsByTagName('*') as $element) {
            $namespace = $element->namespaceURI;

            if ($namespace !== null && $namespace !== self::XHTML_NS) {
                return $namespace;
            }
        }

        return null;
    }

    /**
     * Report an `<a href>` carrying characters a URL may not contain.
     *
     * The permitted set is RFC 3986's: unreserved characters, reserved delimiters and `%` for
     * percent-encoding, with any Unicode letter or digit also accepted. Everything else is listed in
     * document order, in the reference validator's wording, e.g. `R5.patient-url-bad`'s
     * `http://example.com/{[-}]/link.html`, whose `{` and `}` are the only offenders.
     *
     * "Characters" stays plural whatever the count: the corpus has no one-character case showing
     * whether the reference validator writes a singular form.
     *
     * Two more rules follow, both worded after the reference validator and both locating the link by
     * its element path inside the narrative (`div/p/a`) and its text:
     *
     *  - a scheme that identifies rather than locates, such as `urn:uuid:`, is reported because a
     *    reader cannot follow it (`R4.message`);
     *  - a `#fragment` link is deferred to {@see NarrativeLinkTargets}, because its target may be an id
     *    that the walk has not reached yet (`R4.bundle-with-contained`).
     *
     * @return list<FHIRValidationViolation>
     */
    private function checkHref(string $href, string $path, \DOMElement $anchor, NarrativeLinkTargets $links): array
    {
        $violations = [];

        if (preg_match_all(self::URL_INVALID_CHARACTER, $href, $matches) > 0) {
            $quoted = array_map(static fn (string $char): string => "'" . $char . "'", $matches[0]);

            $violations[] = $this->violation(
                $path,
                sprintf(
                    "The URL is not valid because '(URL contains %d Invalid Characters: %s)': %s",
                    count($quoted),
                    implode(',', $quoted),
                    $href,
                ),
                null,
            );
        }

        $colon = strpos($href, ':');
        if ($colon !== false && isset(self::NON_HYPERLINKABLE_SCHEMES[strtolower(substr($href, 0, $colon))])) {
            $violations[] = $this->violation(
                $path,
                sprintf(
                    "Hyperlink scheme '%s' in '%s' at '%s' for '%s' is not a valid hyperlinkable scheme",
                    substr($href, 0, $colon),
                    $href,
                    $this->elementPath($anchor),
                    $this->linkText($anchor),
                ),
                null,
            );
        }

        // A bare `#` points at the top of the page, not at a target.
        if (str_starts_with($href, '#') && $href !== '#') {
            $links->addLink($path, $href, $this->elementPath($anchor), $this->linkText($anchor));
        }

        return $violations;
    }

    /**
     * The anchor's text as the reference validator quotes it: whitespace runs collapsed to one space
     * and trimmed, so a link written across indented lines reads `urn:uuid:…`, not `    urn:uuid:…`.
     */
    private function linkText(\DOMElement $anchor): string
    {
        return trim((string) preg_replace('/\s+/u', ' ', $anchor->textContent));
    }

    /** The element's position inside the narrative as the reference validator writes it, e.g. `div/table/tr/td/a`. */
    private function elementPath(\DOMElement $element): string
    {
        $names = [];
        for ($node = $element; $node instanceof \DOMElement; $node = $node->parentNode) {
            $names[] = (string) $node->localName;
        }

        return implode('/', array_reverse($names));
    }

    /**
     * Named entities the document uses but XML does not define.
     *
     * @return list<string> entity names without the surrounding & and ;, each reported once
     */
    private function undefinedEntities(string $xhtml): array
    {
        if (preg_match_all('/&([A-Za-z][A-Za-z0-9]*);/', $xhtml, $matches) < 1) {
            return [];
        }

        $undefined = [];
        foreach ($matches[1] as $name) {
            if (!isset(self::PREDEFINED_ENTITIES[$name])) {
                $undefined[$name] = true;
            }
        }

        return array_keys($undefined);
    }

    /** @param list<\LibXMLError> $errors */
    private function describeParseError(array $errors): string
    {
        $first = $errors[0] ?? null;

        if ($first === null) {
            return 'the narrative is not well-formed XML';
        }

        $message = trim($first->message);

        // libxml names the two tags the other way round from the reference validator, which reports
        // what it found and then what it expected (`R5.list-xhtml-syntax`).
        if (preg_match('/^Opening and ending tag mismatch: (\S+) line \d+ and (\S+)$/', $message, $tags) === 1) {
            $message = sprintf('Found "</%s>" expecting "</%s>"', $tags[2], $tags[1]);
        }

        return sprintf('%s at line %d column %d', $message, $first->line, $first->column);
    }

    private function isAllowedAttribute(string $element, string $attribute): bool
    {
        return isset(self::GLOBAL_ATTRIBUTES[$attribute])
            || isset(self::ELEMENT_ATTRIBUTES[$element][$attribute]);
    }

    private function isNamespaceDeclaration(\DOMAttr $attribute): bool
    {
        return $attribute->namespaceURI === self::XMLNS_NS
            || $attribute->nodeName     === 'xmlns'
            || str_starts_with($attribute->nodeName, 'xmlns:');
    }

    private function hasParagraphAncestor(\DOMElement $element): bool
    {
        for ($parent = $element->parentNode; $parent instanceof \DOMElement; $parent = $parent->parentNode) {
            if (!str_contains($parent->nodeName, ':') && strtolower((string) $parent->localName) === 'p') {
                return true;
            }
        }

        return false;
    }

    /**
     * Reads a *property* attribute, which resolves through the declaring class. That is what makes
     * this test profile-safe without the checker knowing anything about profiles, and it is the
     * semantics being preserved rather than an incidental detail.
     */
    private function isXhtmlProperty(object $owner, string $property): bool
    {
        // Asked for every property of every node, so the answer is kept per class and property.
        // Only names the walk read off an object arrive here, so the cache is bounded by the
        // declared properties of the classes validated.
        $cached = $this->xhtmlProperties[$owner::class][$property] ?? null;

        if ($cached !== null) {
            return $cached;
        }

        $isXhtml = false;

        foreach ($this->attributes->propertyAttributes($owner, $property, FhirProperty::class) as $attribute) {
            if ($attribute->fhirType === 'xhtml') {
                $isXhtml = true;

                break;
            }
        }

        return $this->xhtmlProperties[$owner::class][$property] = $isXhtml;
    }

    /**
     * An id is held either as a plain string (`Element.id`) or as a generated primitive wrapping one
     * (`Resource.id`).
     */
    private function readString(mixed $value): ?string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_object($value) && property_exists($value, 'value') && is_string($value->value)) {
            return $value->value;
        }

        return null;
    }

    /**
     * The property is declared `xhtml`, so its value is either the raw string or the generated
     * primitive wrapping it. The wrapper is recognised by its own #[FHIRPrimitive] declaration
     * rather than by having a `value` property, so an unrelated element that happens to carry one
     * is never read as narrative markup.
     */
    private function readXhtml(mixed $value): ?string
    {
        if (is_string($value)) {
            return $value;
        }

        if (!is_object($value)) {
            return null;
        }

        // Concrete class only, matching the raw reflection. An ancestor walk would let a subclass of
        // an xhtml wrapper be read as narrative markup where it currently is not.
        foreach ($this->attributes->classAttributes($value, FHIRPrimitive::class) as $attribute) {
            if ($attribute->primitiveType !== 'xhtml') {
                continue;
            }

            return property_exists($value, 'value') && is_string($value->value) ? $value->value : null;
        }

        return null;
    }

    private function fhirVersionOf(object $node): string
    {
        foreach ($this->attributes->classAttributes($node, FHIRComplexType::class) as $attribute) {
            return $attribute->fhirVersion;
        }

        return 'R4';
    }

    private function violation(string $path, string $message, ?string $invariantKey): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: $message,
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: $invariantKey,
        );
    }
}

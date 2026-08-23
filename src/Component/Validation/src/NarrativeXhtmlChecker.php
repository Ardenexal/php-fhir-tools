<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRPrimitive;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;

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
 * ## What is deliberately not checked
 *
 * The XHTML namespace. The XML deserialization path rebuilds `div` from a decoded array and drops
 * every namespace binding on it, so a correct narrative and one carrying the wrong namespace arrive
 * here as the same string. Judging namespaces on that evidence would fail correct documents, so
 * prefixed names are skipped and root namespaces are not inspected at all. The same erasure removes
 * the `<div>` wrapper from a text-only narrative, which is why a value not starting with markup is
 * left alone rather than reported as unparsable.
 */
final class NarrativeXhtmlChecker
{
    private const string XMLNS_NS = 'http://www.w3.org/2000/xmlns/';

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
        'tabindex' => true, 'idref' => true,
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

        return $this->walk($resource, '', $visited);
    }

    /**
     * @param array<int, true> $visited spl_object_id keys of already-visited objects (cycle guard)
     *
     * @return list<FHIRValidationViolation>
     */
    private function walk(object $node, string $path, array &$visited): array
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return [];
        }

        $visited[$id] = true;
        $violations   = [];
        $ref          = new \ReflectionClass($node);

        foreach ($ref->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            if ($prop->isInitialized($node) === false) {
                continue;
            }

            $value   = $prop->getValue($node);
            $subPath = $path === '' ? $prop->getName() : $path . '.' . $prop->getName();

            if ($this->isXhtmlProperty($prop)) {
                $xhtml = $this->readXhtml($value);
                if ($xhtml !== null) {
                    foreach ($this->checkXhtml($xhtml, $subPath, $this->fhirVersionOf($ref)) as $v) {
                        $violations[] = $v;
                    }
                }

                continue;
            }

            if (is_object($value)) {
                foreach ($this->walk($value, $subPath, $visited) as $v) {
                    $violations[] = $v;
                }
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        foreach ($this->walk($item, $subPath . '[' . $i . ']', $visited) as $v) {
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
    private function checkXhtml(string $xhtml, string $path, string $fhirVersion): array
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

            if (!$allowed) {
                $diagnostics[] = $this->violation($path, sprintf("Invalid element name in the XHTML ('%s')", $name), null);
                $breachesTxt1  = true;
            }

            foreach ($element->attributes ?? [] as $attribute) {
                if ($this->isNamespaceDeclaration($attribute)) {
                    continue;
                }

                if ($allowed && $this->isAllowedAttribute($local, strtolower($attribute->nodeName))) {
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

        foreach ($diagnostics as $diagnostic) {
            $violations[] = $diagnostic;
        }

        return $violations;
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

        return sprintf('%s at line %d column %d', trim($first->message), $first->line, $first->column);
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

    private function isXhtmlProperty(\ReflectionProperty $property): bool
    {
        foreach ($property->getAttributes(FhirProperty::class) as $attribute) {
            if ($attribute->newInstance()->fhirType === 'xhtml') {
                return true;
            }
        }

        return false;
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

        foreach ((new \ReflectionClass($value))->getAttributes(FHIRPrimitive::class) as $attribute) {
            if ($attribute->newInstance()->primitiveType !== 'xhtml') {
                continue;
            }

            return property_exists($value, 'value') && is_string($value->value) ? $value->value : null;
        }

        return null;
    }

    /** @param \ReflectionClass<object> $class */
    private function fhirVersionOf(\ReflectionClass $class): string
    {
        foreach ($class->getAttributes(FHIRComplexType::class) as $attribute) {
            return $attribute->newInstance()->fhirVersion;
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

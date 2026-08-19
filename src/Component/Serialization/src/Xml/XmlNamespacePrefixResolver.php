<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Xml;

/**
 * Rewrites namespace-prefixed element names to their local names before XML decoding.
 *
 * FHIR XML permits any prefix bound to the FHIR namespace, so `<f:List xmlns:f="http://hl7.org/fhir">`
 * and `<List xmlns="http://hl7.org/fhir">` are the same document. Symfony's `XmlEncoder` is not
 * namespace-aware for child element names — it keys the decoded array on the raw `nodeName`, prefix
 * included — so `FHIRComplexTypeXmlNormalizer` fails its property lookup on `f:status` and drops the
 * element silently. When the dropped element is required, that surfaces as a bogus
 * "This value should not be blank." about an element the document actually contained.
 *
 * This resolver cannot live inside the normalizer: prefix→URI bindings do not survive
 * `XmlEncoder::decode()`. Root-declared bindings come through as an `@xmlns:*` key, but bindings
 * declared on a child element are destroyed outright — and those are the ones that matter
 * (`<f1:id xmlns:f1="…">`, `<n:div xmlns:n="…">`). Resolution must therefore happen while the DOM,
 * and its namespace scoping, still exists.
 *
 * **Prefix equality is not namespace equality.** Only elements genuinely in the FHIR or XHTML
 * namespace are unprefixed. An element in a foreign namespace — `<f1:id xmlns:f1="http://hl7.org/fhir1">`
 * — is deliberately left alone, so it does not resolve to a FHIR property and is not accepted as valid
 * FHIR content. Reporting that as an explicit "wrong namespace" error is a separate concern; this class
 * only ensures correct content is not lost and incorrect content is not silently promoted.
 *
 * @see .goat-flow/learning-loop/footguns/xml-namespace-prefixes-silently-dropped.md
 */
final class XmlNamespacePrefixResolver
{
    private const FHIR_NS = 'http://hl7.org/fhir';

    private const XHTML_NS = 'http://www.w3.org/1999/xhtml';

    private const XMLNS_NS = 'http://www.w3.org/2000/xmlns/';

    /**
     * The namespaces whose elements are addressable as FHIR content.
     */
    private const RESOLVABLE_NAMESPACES = [self::FHIR_NS, self::XHTML_NS];

    /**
     * Resolve prefixed element names in the FHIR and XHTML namespaces to their local names.
     *
     * Returns the input unchanged when there is nothing to resolve or the document cannot be parsed,
     * so that malformed input still produces the decoder's own error rather than one from here.
     */
    public function resolve(string $xmlData): string
    {
        // Fast path: no prefixed element name anywhere, so the DOM round-trip would be pure cost and
        // pure risk. The overwhelming majority of FHIR XML takes this branch and is byte-identical.
        if (preg_match('/<\s*[A-Za-z_][A-Za-z0-9_.\-]*:/', $xmlData) !== 1) {
            return $xmlData;
        }

        $previousErrorState = libxml_use_internal_errors(true);

        try {
            $document = new \DOMDocument();

            // LIBXML_NONET blocks network access during parse. Entity substitution (LIBXML_NOENT) is
            // deliberately NOT enabled, so DOCTYPE-declared entities are never expanded — this must not
            // become an XXE vector on the way to fixing a namespace bug.
            $loaded = $document->loadXML($xmlData, \LIBXML_NONET);
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previousErrorState);
        }

        if ($loaded === false || !$document->documentElement instanceof \DOMElement) {
            return $xmlData;
        }

        if (!$this->unprefixResolvableElements($document)) {
            return $xmlData;
        }

        $serialized = $document->saveXML();

        return $serialized === false ? $xmlData : $serialized;
    }

    /**
     * Replace every prefixed element in a resolvable namespace with an unprefixed equivalent.
     *
     * @return bool whether anything was rewritten
     */
    private function unprefixResolvableElements(\DOMDocument $document): bool
    {
        $xpath    = new \DOMXPath($document);
        $allNodes = $xpath->query('//*');

        if ($allNodes === false) {
            return false;
        }

        // Snapshot before mutating: the rewrite replaces nodes in the live tree, and iterating a
        // DOMNodeList while replacing its members skips elements.
        $targets = [];

        foreach ($allNodes as $node) {
            if ($node instanceof \DOMElement
                && $node->prefix !== ''
                && in_array($node->namespaceURI, self::RESOLVABLE_NAMESPACES, true)
            ) {
                $targets[] = $node;
            }
        }

        // Document order matters: a parent is rewritten before its children, and because children are
        // *moved* rather than cloned, the snapshotted child nodes stay valid inside their new parent.
        foreach ($targets as $target) {
            $this->replaceWithUnprefixed($target);
        }

        return $targets !== [];
    }

    /**
     * Swap one element for an unprefixed element in the same namespace, preserving attributes and
     * moving children across.
     */
    private function replaceWithUnprefixed(\DOMElement $element): void
    {
        $document  = $element->ownerDocument;
        $parent    = $element->parentNode;
        $localName = $element->localName;

        // localName is only null for node types that carry no name; an element always has one, but the
        // DOM stubs type it nullable, so the rewrite is skipped rather than guessed at.
        if ($document === null || $parent === null || $localName === null || $localName === '') {
            return;
        }

        $replacement = $document->createElementNS($element->namespaceURI, $localName);

        foreach (iterator_to_array($element->attributes) as $attribute) {
            // Namespace declarations are rebuilt by createElementNS and by serialisation; copying them
            // across would re-introduce the prefix binding this rewrite exists to remove.
            if ($attribute->namespaceURI === self::XMLNS_NS) {
                continue;
            }

            if ($attribute->namespaceURI !== null && $attribute->namespaceURI !== '') {
                $replacement->setAttributeNS($attribute->namespaceURI, $attribute->nodeName, $attribute->value);
            } else {
                $replacement->setAttribute($attribute->name, $attribute->value);
            }
        }

        while ($element->firstChild !== null) {
            // appendChild moves the node, so the subtree — including any elements still queued for
            // rewriting — is re-parented rather than duplicated.
            $replacement->appendChild($element->firstChild);
        }

        $parent->replaceChild($replacement, $element);
    }
}

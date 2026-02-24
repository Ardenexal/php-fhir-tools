<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\FHIRPath\Function;

use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\Collection;
use Ardenexal\FHIRTools\Component\FHIRPath\Evaluator\EvaluationContext;

/**
 * htmlChecks(): Boolean
 *
 * FHIR R4 FHIRPath extension function.
 *
 * Checks that a string value (typically Narrative.div) is conformant FHIR
 * Narrative XHTML according to the FHIR R4 Narrative rules:
 *
 *  - The content is well-formed XML (parseable by DOMDocument).
 *  - The root element is a <div> in the XHTML namespace
 *    (http://www.w3.org/1999/xhtml).
 *  - The content does not contain any forbidden elements:
 *    script, form, head, html, base, link, meta, frame, iframe, object.
 *  - The content does not contain event-handler attributes (any attribute
 *    whose name begins with "on", e.g. onclick, onload).
 *  - Every <img> element carries an alt attribute.
 *
 * Returns:
 *  - [true]  — all FHIR Narrative xhtml constraints are satisfied
 *  - [false] — one or more constraints are violated
 *  - []      — input is not exactly one item, or the item is not a string
 *
 * Usage: text.div.htmlChecks()
 *
 * Does NOT depend on Models or CodeGeneration.
 *
 * Spec reference: FHIR R4 FHIRPath supplement; FHIR R4 Narrative §1.3.1.1
 *
 * @author FHIR Tools Contributors
 */
final class HtmlChecksFunction extends AbstractFunction
{
    private const XHTML_NS = 'http://www.w3.org/1999/xhtml';

    /**
     * Elements that must not appear anywhere in a FHIR Narrative div.
     *
     * @var array<int, string>
     */
    private const FORBIDDEN_ELEMENTS = [
        'script',
        'form',
        'head',
        'html',
        'base',
        'link',
        'meta',
        'frame',
        'iframe',
        'object',
    ];

    public function __construct()
    {
        parent::__construct('htmlChecks');
    }

    public function execute(Collection $input, array $parameters, EvaluationContext $context): Collection
    {
        $this->validateParameterCount($parameters, 0);

        // Spec: input must be exactly one item
        if ($input->count() !== 1) {
            return Collection::empty();
        }

        $value = $input->first();

        if (!is_string($value)) {
            return Collection::empty();
        }

        return Collection::single($this->validateXhtml($value));
    }

    /**
     * Run all FHIR Narrative xhtml checks against the given string.
     *
     * Returns true only when every constraint passes; false on the first violation.
     */
    private function validateXhtml(string $xhtml): bool
    {
        // Empty string is not valid XHTML
        if ($xhtml === '') {
            return false;
        }

        // Suppress libxml errors so parse failures are captured as a bool return
        $prevErrorSetting = libxml_use_internal_errors(true);

        $doc    = new \DOMDocument();
        $parsed = $doc->loadXML($xhtml);

        libxml_clear_errors();
        libxml_use_internal_errors($prevErrorSetting);

        if (!$parsed) {
            return false;
        }

        $root = $doc->documentElement;

        if ($root === null) {
            return false;
        }

        // Root element must be <div>
        if ($root->localName !== 'div') {
            return false;
        }

        // Root <div> must carry the XHTML namespace
        if ($root->namespaceURI !== self::XHTML_NS) {
            return false;
        }

        // Forbidden element check (namespace-agnostic local-name match)
        foreach (self::FORBIDDEN_ELEMENTS as $tag) {
            if ($doc->getElementsByTagName($tag)->length > 0) {
                return false;
            }
        }

        // Per-element checks: event attributes and img alt
        $allElements = $doc->getElementsByTagName('*');

        for ($i = 0; $i < $allElements->length; ++$i) {
            $node = $allElements->item($i);

            if (!$node instanceof \DOMElement) {
                continue;
            }

            // Event handler attributes are forbidden (onclick, onload, …)
            $attributes = $node->attributes;
            for ($j = 0; $j < $attributes->length; ++$j) {
                $attr = $attributes->item($j);
                if ($attr instanceof \DOMAttr && str_starts_with(strtolower($attr->name), 'on')) {
                    return false;
                }
            }

            // Every <img> must carry an alt attribute
            if ($node->localName === 'img' && !$node->hasAttribute('alt')) {
                return false;
            }
        }

        return true;
    }
}

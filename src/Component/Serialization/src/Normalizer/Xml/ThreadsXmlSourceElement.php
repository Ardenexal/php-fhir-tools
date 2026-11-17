<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Normalizer\Xml;

/**
 * Hands each child denormalization the source DOM element it was decoded from.
 *
 * XmlEncoder's decode regroups same-named siblings and separates an element's text from its child
 * elements, so anything whose meaning depends on document order has to be read from the source DOM
 * instead (a FHIR `Narrative.div`, a CDA narrative block, a CDA choice group). The DOM element
 * travels in the context under {@see FHIRComplexTypeXmlNormalizer::SOURCE_ELEMENT_CONTEXT_KEY}. Each
 * normalizer consumes its own element and threads the matching child element down, so an object at
 * any depth receives the element it came from rather than an ancestor's.
 *
 * @internal
 */
trait ThreadsXmlSourceElement
{
    /**
     * Remove this object's source element from the context and return it.
     *
     * Removing it keeps a child from mistaking its parent's element for its own: a child only sees
     * an element when {@see withSourceElement()} puts its own there.
     *
     * @param array<string, mixed> $context
     */
    private function takeSourceElement(array &$context): ?\DOMElement
    {
        $element = $context[FHIRComplexTypeXmlNormalizer::SOURCE_ELEMENT_CONTEXT_KEY] ?? null;
        unset($context[FHIRComplexTypeXmlNormalizer::SOURCE_ELEMENT_CONTEXT_KEY]);

        return $element instanceof \DOMElement ? $element : null;
    }

    /**
     * The context for a child decoded from `$element`, or the context unchanged when the element is
     * unknown.
     *
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    private function withSourceElement(array $context, ?\DOMElement $element): array
    {
        if ($element !== null) {
            $context[FHIRComplexTypeXmlNormalizer::SOURCE_ELEMENT_CONTEXT_KEY] = $element;
        }

        return $context;
    }

    /**
     * The child elements of `$parent` with this local name, in document order. Same-named siblings
     * decode in document order too, so the n-th decoded item pairs with the n-th element here.
     *
     * @return list<\DOMElement>
     */
    private function sourceChildrenNamed(?\DOMElement $parent, string $localName): array
    {
        if ($parent === null) {
            return [];
        }

        $matches = [];
        foreach ($parent->childNodes as $child) {
            if ($child instanceof \DOMElement && $child->localName === $localName) {
                $matches[] = $child;
            }
        }

        return $matches;
    }

    /** The resource element inside a resource-holding wrapper, e.g. `<Patient>` in `<resource><Patient>`. */
    private function wrappedResourceElement(?\DOMElement $wrapper, string $resourceName): ?\DOMElement
    {
        return $this->sourceChildrenNamed($wrapper, $resourceName)[0] ?? null;
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;
use Ardenexal\FHIRTools\Component\Metadata\UnknownInput;
use Ardenexal\FHIRTools\Component\Metadata\UnknownInputRecorder;

/**
 * Report document input the deserializer read but could not place on the model.
 *
 * Without this a typo'd element name validates clean: the reader has no property for it and drops
 * it, so the caller gets a clean result on a resource that quietly lost a field. What was dropped
 * is knowable only from what deserialization recorded, never from the model.
 *
 * Wording matches the reference validator character for character, because that text is the
 * contract. JSON names only the property, XML names the owning element too.
 */
final class UnknownInputChecker
{
    public function __construct(
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    /**
     * The namespace every FHIR XML element must be in, named in the wrong-namespace finding.
     */
    private const FHIR_NAMESPACE = 'http://hl7.org/fhir';

    /**
     * Collect a violation for every unplaceable element recorded anywhere under $resource.
     *
     * @param object $resource the deserialized root to walk
     *
     * @return list<FHIRValidationViolation> one error per unplaceable element, empty when the
     *                                       document carried none
     */
    public function check(object $resource): array
    {
        // Nothing recorded anywhere means no walk can find anything, and a clean document is the
        // normal case. Without this the reflection walk runs on every resource of every
        // validate() call, costing more than double the R4 conformance harness wall-clock.
        if (UnknownInputRecorder::isEmpty()) {
            return [];
        }

        $visited = [];

        return $this->walk($resource, '', $visited);
    }

    /**
     * @param object           $node    the object to read records off and descend from
     * @param string           $path    root-relative dotted path to $node, '' at the root
     * @param array<int, true> $visited spl_object_id keys of already-visited objects (cycle guard)
     *
     * @return list<FHIRValidationViolation> findings from $node and everything beneath it
     */
    private function walk(object $node, string $path, array &$visited): array
    {
        $id = spl_object_id($node);

        if (isset($visited[$id])) {
            return [];
        }

        $visited[$id] = true;

        $violations = [];

        foreach (UnknownInputRecorder::forObject($node) as $unknown) {
            $violations[] = $this->violation($unknown, $node, $path);
        }

        foreach ($this->models->publicPropertyNames($node) as $name) {
            if (!$this->models->isPropertyInitialized($node, $name)) {
                continue;
            }

            $value    = $this->models->readInitializedValue($node, $name);
            $propPath = $path === '' ? $name : $path . '.' . $name;

            if (is_object($value)) {
                $violations = [...$violations, ...$this->walk($value, $propPath, $visited)];

                continue;
            }

            if (!is_array($value)) {
                continue;
            }

            foreach ($value as $index => $item) {
                if (is_object($item)) {
                    $violations = [...$violations, ...$this->walk($item, sprintf('%s[%s]', $propPath, $index), $visited)];
                }
            }
        }

        return $violations;
    }

    /**
     * Build the finding in the reference validator's wording for the format it was read from.
     *
     * @param UnknownInput $unknown what could not be placed
     * @param object       $node    the object it was being read into, which names the XML path
     * @param string       $path    root-relative dotted path to $node, '' at the root
     *
     * @return FHIRValidationViolation the error, pathed at the element that carried the unknown input
     */
    private function violation(UnknownInput $unknown, object $node, string $path): FHIRValidationViolation
    {
        // XML carries the owning element and JSON does not, matching the reference validator's two
        // shapes: `Undefined element 'address' at /f:Organization` against `Unrecognized property
        // 'other'`. The `f:` prefix belongs to that wording, not to the document being read.
        if ($unknown->format === UnknownInput::FORMAT_XML) {
            $message = $this->isForeignNamespace($unknown->propertyName)
                ? sprintf("Wrong namespace - expected '%s'", self::FHIR_NAMESPACE)
                : sprintf("Undefined element '%s' at /f:%s", $unknown->propertyName, $this->fhirTypeOf($node));
        } else {
            $message = sprintf("Unrecognized property '%s'", $unknown->propertyName);
        }

        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: $message,
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: null,
        );
    }

    /**
     * Does this element name still carry a namespace prefix?
     *
     * `XmlNamespacePrefixResolver` leaves every non-FHIR namespace prefixed so it stays
     * distinguishable, so a surviving prefix means the wrong namespace for this position. The
     * reference validator words that case differently from an element FHIR does not define.
     *
     * @param string $propertyName the element name as the document spelled it
     *
     * @return bool true when a prefix survived namespace resolution
     */
    private function isForeignNamespace(string $propertyName): bool
    {
        return str_contains($propertyName, ':');
    }

    /**
     * @param object $node the object whose FHIR type name is wanted
     *
     * @return string the FHIR type name (e.g. "Patient", "Organization"), from the attribute when
     *                the model carries one and from the class name otherwise
     */
    private function fhirTypeOf(object $node): string
    {
        // The class's own attribute, not an inherited one: this reports the type of the object in
        // hand, and a profile subclass that declares none falls through to the class-name rule below
        // exactly as it did before.
        $attrs = $this->attributes->classAttributes($node, FhirResource::class);

        if ($attrs !== []) {
            // The attribute carries the spec type name, which the class name only approximates.
            return $attrs[0]->getResourceType();
        }

        $parts = explode('\\', $node::class);
        $name  = (string) end($parts);

        return str_ends_with($name, 'Resource') ? substr($name, 0, -8) : $name;
    }
}

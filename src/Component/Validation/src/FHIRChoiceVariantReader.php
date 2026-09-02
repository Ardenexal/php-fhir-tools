<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Reads a FHIR element path that may name a choice variant rather than a property.
 *
 * @author Ardenexal
 */
final class FHIRChoiceVariantReader
{
    /** Takes the accessor used for ordinary property reads; choice variants are resolved by reflection. */
    public function __construct(
        private readonly PropertyAccessorInterface $propertyAccessor,
    ) {
    }

    /**
     * Every value reachable at $path, following repeating elements and choice variants.
     *
     * A profile constrains a choice by naming the concrete variant: the blood-pressure profile says
     * `Observation.valueQuantity` has max 0, meaning a blood pressure must not carry a plain value. The
     * generator emits that faithfully as a `Count(max: 0)` on path `valueQuantity` — but there is no
     * `$valueQuantity` property. The model has one polymorphic `$value` whose `#[FhirProperty]` metadata
     * lists each variant with the JSON key the specification uses. So the path has to be resolved through
     * that metadata, and the value counted only when it is actually of the variant's type: an Observation
     * holding a CodeableConcept has zero `valueQuantity` occurrences, not one.
     *
     * Without this the read simply fails, the rule is skipped, and a resource the profile forbids
     * validates cleanly.
     *
     * Grouped by parent, never flattened, because cardinality is per occurrence of the element's own
     * parent. `Observation.component.value[x]` has max 1 meaning *each* component carries at most one
     * value — a blood pressure with a systolic and a diastolic component holds two values in total and
     * breaks nothing. Flattening the two into one list reports a conforming document as invalid.
     *
     * @param object|array<string, mixed> $subject Object to read from
     * @param string                      $path    Element path, choice markers and variants included
     *
     * Each group carries both readings of the element, because rules want different ones. `occurrences`
     * is the cardinality view — one entry per occurrence, so a Count can size it. `value` is the
     * property as it stands, which is what every other rule inspects: `All` needs the array itself, and
     * a fixed-value rule needs the object, not a list wrapping it.
     *
     * @return list<array{value: mixed, occurrences: list<mixed>}> One entry per parent the final segment
     *                                                             hangs off; empty when no parent exists,
     *                                                             which means an ancestor is absent rather
     *                                                             than the element occurring zero times
     */
    public function readGroups(object|array $subject, string $path): array
    {
        if ($path === '') {
            return [];
        }

        $segments = explode('.', FHIRElementPath::toPropertyPath($path));
        $leaf     = array_pop($segments);

        $parents = [$subject];

        foreach ($segments as $segment) {
            $next = [];

            foreach ($parents as $node) {
                if (!is_object($node) && !is_array($node)) {
                    continue;
                }

                foreach ($this->readSegment($node, $segment) as $value) {
                    $next[] = $value;
                }
            }

            $parents = $next;
        }

        $groups = [];

        foreach ($parents as $parent) {
            if (!is_object($parent) && !is_array($parent)) {
                continue;
            }

            $groups[] = [
                'value'       => $this->readLeafValue($parent, (string) $leaf),
                'occurrences' => $this->readSegment($parent, (string) $leaf, preserveNulls: true),
            ];
        }

        return $groups;
    }

    /**
     * The element exactly as the model holds it, with no list wrapping and no null filtering.
     *
     * @param object|array<string, mixed> $parent Node the element hangs off
     * @param string                      $leaf   Final step of the element path
     *
     * @return mixed The property value, or the choice variant's value when the path names one
     */
    private function readLeafValue(object|array $parent, string $leaf): mixed
    {
        try {
            return $this->propertyAccessor->getValue($parent, $leaf);
        } catch (\Throwable) {
            if (!is_object($parent)) {
                return null;
            }

            return $this->readChoiceVariant($parent, $leaf)[0] ?? null;
        }
    }

    /**
     * @param object|array<string, mixed> $node          Node reached so far
     * @param string                      $segment       One dot-separated step of the element path
     * @param bool                        $preserveNulls Keep null entries, for the final segment only
     *
     * @return list<mixed> Values at this segment, flattened
     */
    private function readSegment(object|array $node, string $segment, bool $preserveNulls = false): array
    {
        try {
            $value = $this->propertyAccessor->getValue($node, $segment);
        } catch (\Throwable) {
            return is_object($node) ? $this->readChoiceVariant($node, $segment) : [];
        }

        // A null single value is an absent element: zero occurrences, whatever the caller asked for.
        // A null *entry inside a repeating element* is different — the element occurs, it just holds
        // nothing — so at the final segment those entries are kept for the rule to judge.
        if (!is_array($value)) {
            return $value === null ? [] : [$value];
        }

        $found = [];
        foreach ($value as $entry) {
            if ($entry !== null || $preserveNulls) {
                $found[] = $entry;
            }
        }

        return $found;
    }

    /**
     * Resolve $segment as the JSON key of a choice variant, returning the value only when it is of that
     * variant's type.
     *
     * @param object $node    Node whose class carries the choice metadata
     * @param string $segment Candidate variant JSON key, e.g. `valueQuantity`
     *
     * @return list<mixed> One value when the variant is present, empty otherwise
     */
    private function readChoiceVariant(object $node, string $segment): array
    {
        foreach ((new \ReflectionClass($node))->getProperties() as $property) {
            foreach ($property->getAttributes(FhirProperty::class) as $attribute) {
                /** @var FhirProperty $meta the attribute instance, whose variants name the choice's JSON keys */
                $meta = $attribute->newInstance();

                foreach ($meta->variants ?? [] as $variant) {
                    if ($variant['jsonKey'] !== $segment) {
                        continue;
                    }

                    if (!$property->isInitialized($node)) {
                        return [];
                    }

                    $held    = $property->getValue($node);
                    $phpType = $variant['phpType'];

                    // The property holds whichever variant the document used. Counting it for a
                    // different variant's rule would report a Quantity as a CodeableConcept.
                    if ($held === null || !$held instanceof $phpType) {
                        return [];
                    }

                    return [$held];
                }
            }
        }

        return [];
    }
}

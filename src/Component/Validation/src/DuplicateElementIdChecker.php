<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Validation;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirResource;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReader;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRAttributeReaderInterface;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessor;
use Ardenexal\FHIRTools\Component\Metadata\Type\FHIRModelAccessorInterface;

/**
 * Reports an element `id` that repeats another element `id` within the same resource.
 *
 * FHIR element ids exist so a narrative or a reference can point at one element, which only works
 * if each id is unique inside its resource. The reference validator reports every repeat after the
 * first as `Duplicate id value '<id>'`, located at the repeating element.
 *
 * ## Scope: one resource at a time
 *
 * Every resource starts a fresh set of seen ids, including a resource nested inside another
 * (`Bundle.entry.resource`, `contained`). An id on `Bundle.entry[0]` and the same id on
 * `Bundle.entry[2]` are both Bundle elements and do collide; an id inside `entry[1].resource` belongs
 * to that resource and does not collide with the Bundle's. Sharing one set across nested resources
 * would report ids the reference validator accepts.
 *
 * A resource's own `id` (`Resource.id`) is its logical id, not an element id, so it never counts.
 *
 * Neither does `ElementDefinition.id`. It is a path-like element identifier (`Patient.name.given`),
 * and a StructureDefinition repeats every one of them between its snapshot and its differential by
 * design. `R4.structureDefinition-11179-objectClass` is the case: the reference validator reports
 * nothing there, and counting those ids reported four duplicates.
 *
 * ## What cannot be seen
 *
 * An `id` on a `boolean`, `integer` or `decimal` element is lost at deserialization, because those
 * are generated as bare PHP scalars with nowhere to keep it. `<active id="x" value="true"/>` therefore
 * never reaches this pass, and a duplicate that sits on such an element goes unreported.
 */
final class DuplicateElementIdChecker
{
    /** @var array<class-string, bool> keyed by class name */
    private array $isResource = [];

    /** @var array<class-string, bool> keyed by class name */
    private array $isElementDefinition = [];

    public function __construct(
        private readonly FHIRModelAccessorInterface $models = new FHIRModelAccessor(),
        private readonly FHIRAttributeReaderInterface $attributes = new FHIRAttributeReader(),
    ) {
    }

    /**
     * Walk a resource and report every element id that repeats one already seen in its resource.
     *
     * @return list<FHIRValidationViolation>
     */
    public function check(object $resource): array
    {
        $visited = [];
        $seen    = [];

        return $this->walk($resource, '', $seen, $visited);
    }

    /**
     * @param array<string, true> $seen    element ids already met in the enclosing resource
     * @param array<int, true>    $visited spl_object_id keys of the objects on the current descent
     *                                     (cycle guard); an object leaves it once its subtree is walked
     *
     * @return list<FHIRValidationViolation>
     */
    private function walk(object $node, string $path, array &$seen, array &$visited): array
    {
        $objectId = spl_object_id($node);

        if (isset($visited[$objectId])) {
            return [];
        }

        $visited[$objectId] = true;
        $violations         = [];
        $nodeIsResource     = $this->isResource($node);

        // A resource opens its own id scope; any other node shares its enclosing resource's.
        $ownScope = [];

        if ($nodeIsResource) {
            $ids = &$ownScope;
        } else {
            $ids = &$seen;
        }

        foreach ($this->models->initializedPublicValues($node) as $name => $value) {
            $subPath = $path === '' ? $name : $path . '.' . $name;

            if ($name === 'id') {
                if ($nodeIsResource || $this->isElementDefinition($node)) {
                    continue;
                }

                if (is_string($value) && $value !== '') {
                    if (isset($ids[$value])) {
                        $violations[] = $this->violation($path, $value);
                    }

                    $ids[$value] = true;
                }

                continue;
            }

            if (is_object($value)) {
                foreach ($this->walk($value, $subPath, $ids, $visited) as $v) {
                    $violations[] = $v;
                }
            } elseif (is_array($value)) {
                foreach ($value as $i => $item) {
                    if (is_object($item)) {
                        foreach ($this->walk($item, $subPath . '[' . $i . ']', $ids, $visited) as $v) {
                            $violations[] = $v;
                        }
                    }
                }
            }
        }

        // Only a cycle is skipped. The same instance placed twice in a list serializes as two
        // elements carrying the same id, so its second occurrence has to be walked and counted.
        unset($visited[$objectId]);

        return $violations;
    }

    /**
     * The finding sits on the element carrying the repeated id, not on its `id` property, which is
     * where the reference validator locates it too.
     */
    private function violation(string $path, string $id): FHIRValidationViolation
    {
        return new FHIRValidationViolation(
            severity: 'error',
            path: $path,
            message: sprintf("Duplicate id value '%s'", $id),
            constraintClass: self::class,
            profileGroup: null,
            invariantKey: null,
        );
    }

    /**
     * Read through the hierarchy: a generated resource profile such as `SearchSetBundleProfile`
     * extends `BundleResource` without redeclaring `#[FhirResource]`, and is still a resource.
     */
    private function isResource(object $node): bool
    {
        return $this->isResource[$node::class] ??= $this->attributes->declaresInHierarchy($node, FhirResource::class);
    }

    /**
     * R4 and R4B generate ElementDefinition as a backbone element of its own name, R5 as a complex
     * type, so both attributes are read, through the hierarchy so that a data-type profile on
     * ElementDefinition counts too.
     */
    private function isElementDefinition(object $node): bool
    {
        $key = $node::class;

        if (isset($this->isElementDefinition[$key])) {
            return $this->isElementDefinition[$key];
        }

        $found = false;

        foreach ($this->attributes->classAttributesInHierarchy($node, FHIRComplexType::class) as $complex) {
            $found = $found || $complex->typeName === 'ElementDefinition';
        }

        foreach ($this->attributes->classAttributesInHierarchy($node, FHIRBackboneElement::class) as $backbone) {
            $found = $found || $backbone->elementPath === 'ElementDefinition';
        }

        return $this->isElementDefinition[$key] = $found;
    }
}

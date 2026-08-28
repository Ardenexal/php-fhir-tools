<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Serialization\Metadata;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

/**
 * Locates the class-level #[LogicalModel] attribute for an object or class name.
 *
 * CDA datatypes and clinical classes carry #[LogicalModel] rather than #[FhirResource]/
 * #[FHIRComplexType], and AU classes inherit it from their core parent, so the lookup walks the
 * parent chain. Shared by the CDA XML/JSON normalizers and the serialization service so the
 * parent-chain walk lives in one place rather than being reimplemented per call site.
 *
 * @author Ardenexal
 */
trait LogicalModelLocatorTrait
{
    /**
     * Return the #[LogicalModel] attribute declared by the subject's class or an ancestor, or null
     * when none is present (i.e. the subject is not a CDA logical model).
     *
     * @param object|class-string|string $subject An instance or fully-qualified class name
     */
    private function findLogicalModelAttribute(object|string $subject): ?LogicalModel
    {
        $located = $this->locateLogicalModel($subject);

        return $located === null ? null : $located[1];
    }

    /**
     * The XML element name a logical model appears under, or null when the subject is not a logical
     * model.
     *
     * This is NOT `LogicalModel::$name`. That field is the StructureDefinition name — a *type*
     * identifier — and the two diverge whenever a definition refines another published type:
     * `au-ClinicalDocument` is a profile identifier that was never an element name, and CDA requires
     * `<ClinicalDocument>` on the wire. The same rule is already applied to resources one level up
     * (a profiled `Parameters` is still `Parameters`); it simply had no logical-model equivalent.
     *
     * The chain followed here is `LogicalModel::$refines`, which the generator emits from the
     * StructureDefinition's own `type` field, so the element name is resolved from what the
     * definition states rather than inferred. A model that refines nothing carries no `refines` and
     * its own `name` is the element name — which is the common case, and every core CDA type.
     *
     * Inference from the shape of `url` and `name` is deliberately not attempted. It is unsound on
     * real upstream data: `au-Place` is published under the *core* HL7 URL
     * (`http://hl7.org/cda/stds/core/StructureDefinition/au-Place`) despite refining core `Place`,
     * so no comparison of authorities can classify it, while AU's own `code` type derives from `CE`
     * without refining it and would be misread as a refinement. Both are decided correctly by
     * `refines`.
     *
     * @param object|class-string|string $subject An instance or fully-qualified class name
     *
     * @return string|null The element name to write the model under, or null when it is not a logical model
     */
    private function logicalModelElementName(object|string $subject): ?string
    {
        $located = $this->locateLogicalModel($subject);

        if ($located === null) {
            return null;
        }

        [$class, $model] = $located;

        // A refinement of a refinement is legal, so the chain is followed to the type that actually
        // named the element. The refined type is always an ancestor — the generator derives both
        // `refines` and `extends` from the same `type` field — so each hop is a search up the parent
        // chain rather than a global registry lookup. `$seen` guards against a cycle in malformed
        // metadata, which would otherwise hang serialization.
        $seen = [$model->url => true];

        while ($model->refines !== null && !isset($seen[$model->refines])) {
            $seen[$model->refines] = true;
            $refined               = self::ancestorDeclaring($class, $model->refines);

            if ($refined === null) {
                break;
            }

            [$class, $model] = $refined;
        }

        return $model->name;
    }

    /**
     * Find the ancestor of `$class` whose declared #[LogicalModel] carries `$url`.
     *
     * @param \ReflectionClass<object> $class Class to search upwards from
     * @param string                   $url   Canonical URL of the refined StructureDefinition
     *
     * @return array{0: \ReflectionClass<object>, 1: LogicalModel}|null The refined type's class and
     *                                                                  attribute, or null when no
     *                                                                  ancestor declares that URL
     */
    private static function ancestorDeclaring(\ReflectionClass $class, string $url): ?array
    {
        for ($parent = $class->getParentClass(); $parent !== false; $parent = $parent->getParentClass()) {
            // Declared attributes only: an inherited one would report the grandparent's URL as this
            // parent's and stop the walk a level early.
            $attributes = $parent->getAttributes(LogicalModel::class);

            if ($attributes === []) {
                continue;
            }

            $model = $attributes[0]->newInstance();

            if ($model->url === $url) {
                return [$parent, $model];
            }
        }

        return null;
    }

    /**
     * The class declaring the nearest #[LogicalModel] in the subject's hierarchy, with that
     * attribute — or null when there is none.
     *
     * @param object|class-string|string $subject An instance or fully-qualified class name
     *
     * @return array{0: \ReflectionClass<object>, 1: LogicalModel}|null The declaring class paired with
     *                                                                  its attribute, so callers can keep
     *                                                                  walking the chain from where the
     *                                                                  attribute was actually found
     */
    private function locateLogicalModel(object|string $subject): ?array
    {
        if (is_string($subject) && !class_exists($subject)) {
            return null;
        }

        try {
            $refl = new \ReflectionClass($subject);

            do {
                $attributes = $refl->getAttributes(LogicalModel::class);

                if ($attributes !== []) {
                    return [$refl, $attributes[0]->newInstance()];
                }

                $refl = $refl->getParentClass();
            } while ($refl !== false);

            return null;
        } catch (\ReflectionException) {
            return null;
        }
    }
}

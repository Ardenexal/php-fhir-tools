<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Type;

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
     * `refines` alone does not settle it, though, because `type` naming another published type
     * covers two different things: a profile of that type, and a definition that merely reuses it as
     * its base while naming an element of its own. Only the first may take the refined type's name.
     * {@see isElementName()} separates them, and the walk stops as soon as it reaches a definition
     * that names an element.
     *
     * Inference from the shape of the *URL* is deliberately not attempted — it is unsound on real
     * upstream data: `au-Place` is published under the *core* HL7 URL
     * (`http://hl7.org/cda/stds/core/StructureDefinition/au-Place`) despite refining core `Place`,
     * so no comparison of authorities can classify it, while AU's own `code` type derives from `CE`
     * without refining it and would be misread as a refinement. Both are decided correctly by
     * `refines` plus the name test.
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
        // chain rather than a global registry lookup. That also bounds the walk: every hop reassigns
        // `$class` to a strict ancestor of itself, and a class hierarchy is finite and acyclic, so
        // the loop terminates on its own however malformed the metadata is.
        //
        // `$seen` is therefore a determinism guard, not a termination one. Two classes in one chain
        // declaring the same `url` is the case it covers: without it the walk could follow a link
        // back to a type it already resolved through, making the element name depend on where the
        // walk started rather than on what the definitions say.
        $seen = [$model->url => true];

        while (!self::isElementName($model->name) && $model->refines !== null && !isset($seen[$model->refines])) {
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
     * Whether a StructureDefinition name is an element name in its own right, rather than a profile
     * identifier standing in for one.
     *
     * A refinement may only take the refined type's name when its own name is not usable on the
     * wire, and the two are told apart by the characters they are built from. HL7 V3 and CDA name
     * every type and element out of `[A-Za-z0-9_]` — `ClinicalDocument`, `templateId`, `IVXB_PQ` —
     * while the realm-prefixed profile identifiers published against them are not: no
     * `xmlSerializedName` anywhere in the generated CDA models contains a character outside that
     * set, so a name that does cannot be an element name.
     *
     * This is what keeps the walk off definitions that reuse another type without profiling it.
     * `asQualifiedEntity` declares `type` as AU's own `asQualifications`, but both are real and
     * *different* elements — `Entity.asQualifiedEntity` and `Person.asQualifications` — so following
     * the link would emit another class's element. `templateId` declares `type` as the `II`
     * datatype, and `II` names no element at all. Both keep their own name.
     *
     * The rule declines rather than over-reaches. A package whose profile identifiers happen to be
     * plain names would stop being renamed and ship those identifiers, which is the behaviour from
     * before `refines` existed — not a new way to emit the wrong element.
     *
     * @param string $name The StructureDefinition name from a #[LogicalModel] attribute
     *
     * @return bool True when the name may be written to the wire as-is
     */
    private static function isElementName(string $name): bool
    {
        // Every character drawn from the HL7 V3 name alphabet; anything else marks a profile identifier.
        return preg_match('/^[A-Za-z0-9_]++$/', $name) === 1;
    }

    /**
     * Find the ancestor of `$class` whose declared #[LogicalModel] carries `$url`.
     *
     * @param class-string $class Class to search upwards from
     * @param string       $url   Canonical URL of the refined StructureDefinition
     *
     * @return array{0: class-string, 1: LogicalModel}|null The refined type's class name and
     *                                                      attribute, or null when no ancestor
     *                                                      declares that URL
     */
    private static function ancestorDeclaring(string $class, string $url): ?array
    {
        for ($parent = get_parent_class($class); $parent !== false; $parent = get_parent_class($parent)) {
            // Declared attributes only: an inherited one would report the grandparent's URL as this
            // parent's and stop the walk a level early.
            $model = self::declaredLogicalModel($parent);

            if ($model !== null && $model->url === $url) {
                return [$parent, $model];
            }
        }

        return null;
    }

    /**
     * The #[LogicalModel] declared directly on `$class`, ignoring anything inherited.
     *
     * This is the one place the trait touches reflection. It is kept to a single call taking a class
     * name and returning an attribute instance, so no reflection handle is ever passed between
     * methods or handed back to a caller — the boundary rule this consolidation exists to enforce.
     *
     * @param class-string $class Class to read the declared attribute from
     *
     * @return LogicalModel|null The attribute this class declares itself, or null when it declares none
     */
    private static function declaredLogicalModel(string $class): ?LogicalModel
    {
        try {
            $attributes = (new \ReflectionClass($class))->getAttributes(LogicalModel::class);
        } catch (\ReflectionException) {
            return null;
        }

        return $attributes === [] ? null : $attributes[0]->newInstance();
    }

    /**
     * The class declaring the nearest #[LogicalModel] in the subject's hierarchy, with that
     * attribute — or null when there is none.
     *
     * @param object|class-string|string $subject An instance or fully-qualified class name
     *
     * @return array{0: class-string, 1: LogicalModel}|null The declaring class name paired with its
     *                                                      attribute, so callers can keep walking the
     *                                                      chain from where the attribute was actually
     *                                                      found
     */
    private function locateLogicalModel(object|string $subject): ?array
    {
        $class = is_object($subject) ? $subject::class : $subject;

        if (!class_exists($class)) {
            return null;
        }

        for ($current = $class; $current !== false; $current = get_parent_class($current)) {
            $model = self::declaredLogicalModel($current);

            if ($model !== null) {
                return [$current, $model];
            }
        }

        return null;
    }
}

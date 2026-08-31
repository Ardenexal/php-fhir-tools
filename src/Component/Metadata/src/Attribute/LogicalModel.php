<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute;

/**
 * Marks a generated class as a logical model StructureDefinition.
 *
 * Applied to classes generated from StructureDefinitions with kind=logical,
 * derivation=specialization — including CDA R2 and AU CDA extension classes.
 * The xmlNamespace field is non-null only for XML-only serialisation targets
 * (e.g. CDA uses urn:hl7-org:v3). A null value means JSON serialisation
 * is permitted.
 *
 * `name` is the StructureDefinition name — a *type* identifier, and not always an XML element name.
 * The two diverge when a definition refines another published type instead of introducing one:
 * `au-ClinicalDocument` is a profile identifier, and CDA requires `<ClinicalDocument>` on the wire.
 * `refines` records that relationship — the canonical URL of the type this one refines, taken from
 * the StructureDefinition's own `type` field — so consumers can resolve the wire name by following
 * it rather than guessing from the shape of `url` and `name`. It is null for a definition that
 * introduces a type of its own, which is the common case and where `name` is already the element
 * name.
 *
 * `propertyOrder` is the class's content model: every property name it can serialize — its own and
 * every inherited one — in the order the StructureDefinition publishes them. PHP reflection cannot
 * answer this. `ReflectionClass::getProperties()` returns a class's own properties first and its
 * ancestors' last, so an element a CDA parent contributes (`InfrastructureRoot` gives `realmCode`,
 * `typeId` and `templateId`, which the content model puts first) reflects last. Nor can the class
 * hierarchy answer it: a child's own element may belong in the *middle* of its parent's sequence,
 * as AU's `completionCode` does on `ClinicalDocument`. Only the definition carries the positions, so
 * the generator records them here and serializers order by this list rather than by reflection.
 *
 * Names are case-sensitive: CDA `Section` declares both an `ID` XML attribute and an `id` element,
 * which are distinct properties. Never key a lookup on a case-folded name.
 *
 * The list is empty for a class generated before this field existed, and consumers MUST treat empty
 * as "no ordering opinion" and fall back to their previous behaviour.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
final class LogicalModel
{
    /**
     * @param list<string> $propertyOrder Every serializable property name, own and inherited, in
     *                                    published content-model order; empty when unknown
     */
    public function __construct(
        public readonly string $url,
        public readonly string $name,
        public readonly string $fhirVersion,
        public readonly ?string $xmlNamespace = null,
        public readonly ?string $refines = null,
        public readonly array $propertyOrder = [],
    ) {
    }
}

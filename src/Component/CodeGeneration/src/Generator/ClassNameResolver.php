<?php

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Generator;

use function Symfony\Component\String\u;

class ClassNameResolver
{
    /**
     * Definitions whose `name` alone does not yield a unique class name.
     *
     * A FHIR definition's `name` is not unique across a package, but it is the only thing the default
     * rule reads — so two definitions sharing a name generate the same class, and whichever is written
     * last silently wins. Nothing detects that: the losing definition simply has no class, and its URL
     * resolves to nothing at deserialization time.
     *
     * Worse than the data loss, the winner is decided by the order the package's files happen to be
     * enumerated in, which differs between filesystems. The same command on the same package produced
     * `cqf-publicationDate` locally and `artifact-publicationDate` on CI, so the committed models
     * flapped between runners and the model-generation drift check flagged a difference that
     * regenerating could not fix.
     *
     * Keyed by URL because that is the definition's actual identity — `name` is what collides.
     *
     * ## Which side keeps the plain name
     *
     * The incumbent does, in every case below: `PublicationDateExtension`, `PartOfExtension` and
     * `TimezoneCodeExtension` already exist with those meanings, and renaming them would break every
     * consumer that references them. Only the definition that was being *dropped* gains a name, so the
     * change adds three classes and alters none.
     *
     * This is a judgement call worth stating plainly: on merit `artifact-publicationDate` has the
     * better claim to the plain name — it is the modern definition with 22 element contexts, where
     * `cqf-publicationDate` carries one. Merit loses to backwards compatibility here. Revisit it if a
     * BC-breaking regen is happening for other reasons.
     */
    private const array DEFINITION_TO_CLASS_OVERRIDES = [
        'http://hl7.org/fhir/ValueSet/claim-use' => 'ClaimUse',

        // Collides with cqf-publicationDate, both named `PublicationDate`.
        'http://hl7.org/fhir/StructureDefinition/artifact-publicationDate' => 'ArtifactPublicationDate',
        // Collides with cqf-partOf, both named `PartOf`.
        'http://hl7.org/fhir/StructureDefinition/event-partOf' => 'EventPartOf',
        // Collides with timezone, both named `TimezoneCode`.
        'http://hl7.org/fhir/StructureDefinition/tz-code' => 'TzCode',
    ];

    public static function resolveClassName(string $definitionUrl, string $definitionName): string
    {
        return self::DEFINITION_TO_CLASS_OVERRIDES[$definitionUrl] ?? u($definitionName)->pascal()->toString();
    }
}

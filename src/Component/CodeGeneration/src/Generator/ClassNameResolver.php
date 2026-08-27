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

    /**
     * PHP reserved words that cannot be used as a class name (case-insensitive). A logical-model
     * type code such as the CDA datatype `INT` pascal-cases to a value that collides with the
     * reserved `int` type keyword; these get a `Type` suffix via {@see logicalModelClassName()}.
     */
    private const array RESERVED_CLASS_NAMES = [
        'int', 'float', 'bool', 'string', 'true', 'false', 'null', 'void',
        'iterable', 'object', 'mixed', 'never', 'self', 'parent', 'static',
        'enum', 'callable', 'array',
    ];

    public static function resolveClassName(string $definitionUrl, string $definitionName): string
    {
        return self::DEFINITION_TO_CLASS_OVERRIDES[$definitionUrl] ?? u($definitionName)->pascal()->toString();
    }

    /**
     * Canonical URL infix for the AU CDA schema's own (non-core) definitions — both
     * StructureDefinitions (`.../cda/StructureDefinition/au-*`) and ValueSets
     * (`.../cda/ValueSet/dh-*`). Matching the shared `/cda/` segment covers both so AU classes AND
     * AU enums are `Au`-prefixed and never case-collide with their core counterparts (PHP class
     * names are case-insensitive, e.g. AU `Entitynameuse` vs core `EntityNameUse`).
     */
    private const string AU_CDA_NAMESPACE = 'ns.electronichealth.net.au/cda/';

    /**
     * Class name for a logical-model (CDA) type: the standard resolved name, with a `Type` suffix
     * appended when it would otherwise be a PHP reserved word (e.g. `INT` → `INTType`). Kept
     * separate from {@see resolveClassName()} so FHIR primitive naming (e.g. `StringPrimitive`)
     * is unaffected.
     *
     * AU CDA additions are named from the URL id, not the `name` field, and prefixed `Au`: their
     * `name` fields collide with core CDA classes (e.g. `au-Address` is named `AD`, `au-Telecom`
     * is named `TEL`) and several are typos or non-identifiers (`addr` → name `addrress`,
     * `PolicyOrAccount` → name `Policy or Account`). Deriving from the id and prefixing `Au`
     * yields a stable, collision-free name (`au-ClinicalDocument` → `AuClinicalDocument`,
     * `au-Address` → `AuAddress`, `addr` → `AuAddr`); a leading `au-` in the id is folded into the
     * prefix rather than doubled.
     */
    public static function logicalModelClassName(string $definitionUrl, string $definitionName): string
    {
        if (str_contains($definitionUrl, self::AU_CDA_NAMESPACE)) {
            $id   = substr($definitionUrl, (int) strrpos($definitionUrl, '/') + 1);
            $id   = (string) preg_replace('/^au-/i', '', $id);
            $name = 'Au' . u($id)->pascal()->toString();
        } else {
            $name = self::resolveClassName($definitionUrl, $definitionName);
        }

        if (in_array(strtolower($name), self::RESERVED_CLASS_NAMES, true)) {
            $name .= 'Type';
        }

        return $name;
    }

    /**
     * Class name for a CDA ValueSet enum: the resolved name with a leading `CDA` qualifier
     * stripped (the bundled CDA ValueSets are named `CDANullFlavor`, `CDAActClass`, … but the
     * generated enums live in the CDA-specific `CdaModels\Enum\` namespace, so the prefix is
     * redundant — e.g. `CDANullFlavor` → `NullFlavor`). The reserved-word `Type` suffix from
     * {@see logicalModelClassName()} still applies (e.g. a hypothetical `CDAInt` → `IntType`).
     */
    public static function cdaEnumClassName(string $definitionUrl, string $definitionName): string
    {
        $name = self::logicalModelClassName($definitionUrl, $definitionName);

        // Strip the redundant `CDA` qualifier when the remainder still starts a valid PascalCase
        // class name (e.g. `CDANullFlavor` → `NullFlavor`). All 26 bundled CDA ValueSet names are
        // `CDA` + an uppercase word, so this is exact; the guard keeps a hypothetical non-prefixed
        // name like `Cdash...` untouched.
        if (str_starts_with($name, 'CDA') && strlen($name) > 3 && ctype_upper($name[3])) {
            $name = substr($name, 3);
        }

        return $name;
    }
}

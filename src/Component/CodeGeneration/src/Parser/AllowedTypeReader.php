<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Parser;

use function Symfony\Component\String\u;

/**
 * Resolves the set of concrete FHIR types an OperationDefinition parameter may carry.
 *
 * A parameter typed `Element` (or `*`) is polymorphic: the definition constrains it to a closed
 * list of concrete types, and each becomes a `value[x]` variant on the wire. That list lives in
 * one of two places, and reading only one of them is wrong on every shipped FHIR version:
 *
 *  1. `parameter.allowedType[]` — a first-class element added in R5. Reading only this yields an
 *     EMPTY set on R4/R4B, where the element does not exist.
 *  2. The `operationdefinition-allowed-type` extension — the legacy carrier. Reading only this
 *     is forward-incompatible with definitions that adopt the R5 element.
 *
 * The spec-obvious correction — branch on version, extension for R4/R4B and `allowedType` for R5 —
 * is worse than either. No OperationDefinition shipped in the R4, R4B or R5 core packages populates
 * `allowedType`; all five definitions carrying allowed-type information use the extension
 * exclusively, even on R5 and even though the extension is marked `endFhirVersion 4.3`. A
 * version-branched reader therefore returns zero variants for R5 `CodeSystem/$lookup` today.
 *
 * This reader unions both sources on every version. The union is deduplicated and sorted, so R4
 * and R5 produce an equal set for the same parameter despite the two versions listing the
 * extension entries in different orders.
 *
 * Type codes are returned verbatim, not mapped to PHP types: resolving a FHIR type code to a
 * generated class requires BuilderContext, which is a generator concern. Callers pair each code
 * with {@see self::jsonKeyFor()} to obtain the `value[x]` element name.
 *
 * @author Ardenexal
 */
final class AllowedTypeReader
{
    private const string ALLOWED_TYPE_URL = 'http://hl7.org/fhir/StructureDefinition/operationdefinition-allowed-type';

    /**
     * Read the concrete types a single OperationDefinition parameter permits.
     *
     * Returns an empty list for a monomorphic parameter (one with a concrete `type` such as `code`
     * or `Coding`), which is the correct answer: such a parameter has exactly one type and needs no
     * variant set. An empty list is therefore not an error signal — callers distinguish
     * "polymorphic with no declared variants" from "monomorphic" by inspecting `parameter.type`.
     *
     * @param array<string, mixed> $parameter One entry from `OperationDefinition.parameter[]`
     *                                        (or from a nested `part[]`)
     *
     * @return list<string> Deduplicated FHIR type codes, sorted for version-stable comparison
     */
    public function read(array $parameter): array
    {
        $types = [];

        // Source 1: the R5 first-class element. Currently unpopulated across all core packages,
        // handled for forward compatibility.
        $allowedTypes = $parameter['allowedType'] ?? [];

        if (is_array($allowedTypes)) {
            foreach ($allowedTypes as $allowedType) {
                if (is_string($allowedType) && $allowedType !== '') {
                    $types[] = $allowedType;
                }
            }
        }

        // Source 2: the legacy extension. The only live source in R4, R4B and R5 alike.
        $extensions = $parameter['extension'] ?? [];

        if (is_array($extensions)) {
            foreach ($extensions as $extension) {
                if (!is_array($extension) || ($extension['url'] ?? null) !== self::ALLOWED_TYPE_URL) {
                    continue;
                }

                // The extension's own StructureDefinition types Extension.value[x] as `uri` in every
                // published snapshot checked (R4, R4B and the R5 extensions pack, 2026-08-07), and
                // valueUri is what the core packages emit. valueCode is a defensive fallback for a
                // publisher that emits the type code as `code`; no shipped package does today.
                $value = $extension['valueUri'] ?? $extension['valueCode'] ?? null;

                if (is_string($value) && $value !== '') {
                    $types[] = $value;
                }
            }
        }

        $types = array_values(array_unique($types));
        sort($types);

        return $types;
    }

    /**
     * Map a FHIR type code to its `value[x]` element name.
     *
     * Matches the convention already used for choice elements elsewhere in generation
     * (see FHIRExtensionGenerator::buildMultiTypeValueConstructor): the type code is
     * pascal-cased and appended to `value`, giving `code` => `valueCode`,
     * `dateTime` => `valueDateTime`, `Coding` => `valueCoding`.
     */
    public static function jsonKeyFor(string $fhirType): string
    {
        return 'value' . u($fhirType)->pascal()->toString();
    }
}

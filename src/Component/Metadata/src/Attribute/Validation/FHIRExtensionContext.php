<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation;

/**
 * Declares a permitted context for a FHIR named extension.
 *
 * Maps to `StructureDefinition.context[]` — each entry describes an element path,
 * canonical extension URL, or FHIRPath expression identifying where the extension
 * is allowed to appear. Multiple attributes use OR semantics: the extension is
 * valid if it appears at *any* of the listed contexts.
 *
 * Supported types:
 *   - `element`   — The extension is permitted on the element at the given path
 *                   (e.g. `Patient.name`) or any of its sub-elements.
 *   - `extension` — The extension must be nested inside another named extension
 *                   identified by its canonical URL. Evaluated against the chain of
 *                   enclosing extension URLs; denial requires a fully-known chain,
 *                   an unreadable enclosing URL defers.
 *   - `fhirpath`  — A FHIRPath expression evaluated against the element bearing the
 *                   extension. Only a confident single boolean `false` denies; empty
 *                   results and FHIRPath engine errors (FHIRPathException) defer.
 *
 * @see https://www.hl7.org/fhir/structuredefinition-definitions.html#StructureDefinition.context
 *
 * @author Ardenexal
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class FHIRExtensionContext
{
    /**
     * @param string $type       Context type: 'element' | 'extension' | 'fhirpath'
     * @param string $expression Element path, extension canonical URL, or FHIRPath expression
     */
    public function __construct(
        public readonly string $type,
        public readonly string $expression,
    ) {
    }
}

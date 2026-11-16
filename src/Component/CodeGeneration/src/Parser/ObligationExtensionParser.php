<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Parser;

use Ardenexal\FHIRTools\Component\Metadata\Profile\ObligationExtensionReader;

/**
 * Parses FHIR obligation extensions from a StructureDefinition snapshot element's extension array.
 *
 * The obligation extension URL is http://hl7.org/fhir/StructureDefinition/obligation.
 * Each obligation is a complex extension with inner extension[] sub-entries carrying the code,
 * actor, filter, and documentation fields.
 *
 * Delegates to {@see ObligationExtensionReader} in Metadata, which runtime profile compilation also uses.
 *
 * @author Ardenexal
 */
final class ObligationExtensionParser
{
    /**
     * Extract all obligation entries from a snapshot element's extension array.
     *
     * @param array<int, array<string, mixed>> $elementExtensions The `extension[]` array from a snapshot element
     *
     * @return list<array{code: string, actor: ?string, filter: ?string, documentation: ?string}>
     */
    public function parse(array $elementExtensions): array
    {
        return (new ObligationExtensionReader())->parse($elementExtensions);
    }
}

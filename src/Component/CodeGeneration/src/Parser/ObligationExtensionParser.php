<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CodeGeneration\Parser;

/**
 * Parses FHIR obligation extensions from a StructureDefinition snapshot element's extension array.
 *
 * The obligation extension URL is http://hl7.org/fhir/StructureDefinition/obligation.
 * Each obligation is a complex extension with inner extension[] sub-entries carrying the code,
 * actor, filter, and documentation fields.
 *
 * @author Ardenexal
 */
final class ObligationExtensionParser
{
    private const string OBLIGATION_URL = 'http://hl7.org/fhir/StructureDefinition/obligation';

    /**
     * Extract all obligation entries from a snapshot element's extension array.
     *
     * @param array<int, array<string, mixed>> $elementExtensions The `extension[]` array from a snapshot element
     *
     * @return list<array{code: string, actor: ?string, filter: ?string, documentation: ?string}>
     */
    public function parse(array $elementExtensions): array
    {
        $obligations = [];

        foreach ($elementExtensions as $ext) {
            if (($ext['url'] ?? '') !== self::OBLIGATION_URL) {
                continue;
            }

            /** @var array<int, array<string, mixed>> $inner */
            $inner = $ext['extension'] ?? [];

            $code          = null;
            $actor         = null;
            $filter        = null;
            $documentation = null;

            foreach ($inner as $sub) {
                $url = (string) ($sub['url'] ?? '');

                match ($url) {
                    'code'          => $code = isset($sub['valueCode']) && is_string($sub['valueCode'])
                        ? $sub['valueCode']
                        : null,
                    'actor'         => $actor = isset($sub['valueCanonical']) && is_string($sub['valueCanonical'])
                        ? $sub['valueCanonical']
                        : null,
                    'filter'        => $filter = isset($sub['valueString']) && is_string($sub['valueString'])
                        ? $sub['valueString']
                        : null,
                    'documentation' => $documentation = isset($sub['valueMarkdown']) && is_string($sub['valueMarkdown'])
                        ? $sub['valueMarkdown']
                        : (isset($sub['valueString']) && is_string($sub['valueString'])
                            ? $sub['valueString']
                            : null),
                    default         => null,
                };
            }

            if ($code === null) {
                // Malformed obligation extension — skip and log notice
                trigger_error(
                    'Skipping obligation extension with missing code sub-extension',
                    \E_USER_NOTICE,
                );
                continue;
            }

            $obligations[] = [
                'code'          => $code,
                'actor'         => $actor,
                'filter'        => $filter,
                'documentation' => $documentation,
            ];
        }

        return $obligations;
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Metadata\Profile;

/**
 * A rule the compiler found in a StructureDefinition but could not translate.
 *
 * @author Ardenexal
 */
final readonly class CompilerDiagnostic
{
    /**
     * @param string               $message   What could not be translated and what that means for validation
     * @param string               $elementId Id of the element concerned, e.g. 'Observation.component'
     * @param array<string, mixed> $context   Extra detail, such as the profile URL
     */
    public function __construct(
        public string $message,
        public string $elementId,
        public array $context = [],
    ) {
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description A human-readable display of key concepts to represent the citation.
 */
#[FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.summary', fhirVersion: 'R5')]
class FHIRCitationSummary extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null style Format for display of the citation summary */
        public ?\FHIRCodeableConcept $style = null,
        /** @var FHIRMarkdown|null text The human-readable display of the citation summary */
        #[NotBlank]
        public ?\FHIRMarkdown $text = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description One or more sets of investigations (signs, symptoms, etc.). The actual grouping of investigations varies greatly depending on the type and context of the assessment. These investigations may include data generated during the assessment process, or data previously generated and recorded that is pertinent to the outcomes.
 */
#[FHIRBackboneElement(parentResource: 'ClinicalImpression', elementPath: 'ClinicalImpression.investigation', fhirVersion: 'R4B')]
class FHIRClinicalImpressionInvestigation extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null code A name/code for the set */
        #[NotBlank]
        public ?\FHIRCodeableConcept $code = null,
        /** @var array<FHIRReference> item Record of a specific investigation */
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ClinicalImpression;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description One or more sets of investigations (signs, symptoms, etc.). The actual grouping of investigations varies greatly depending on the type and context of the assessment. These investigations may include data generated during the assessment process, or data previously generated and recorded that is pertinent to the outcomes.
 */
#[FHIRBackboneElement(parentResource: 'ClinicalImpression', elementPath: 'ClinicalImpression.investigation', fhirVersion: 'R4')]
class ClinicalImpressionInvestigation extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code A name/code for the set */
        #[NotBlank]
        public ?CodeableConcept $code = null,
        /** @var array<Reference> item Record of a specific investigation */
        public array $item = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

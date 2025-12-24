<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;

/**
 * @description Specifics for when this is a contraindication.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ClinicalUseDefinition', elementPath: 'ClinicalUseDefinition.contraindication', fhirVersion: 'R4B')]
class FHIRClinicalUseDefinitionContraindication extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null diseaseSymptomProcedure The situation that is being documented as contraindicating against this item */
        public ?FHIRCodeableReference $diseaseSymptomProcedure = null,
        /** @var FHIRCodeableReference|null diseaseStatus The status of the disease or symptom for the contraindication */
        public ?FHIRCodeableReference $diseaseStatus = null,
        /** @var array<FHIRCodeableReference> comorbidity A comorbidity (concurrent condition) or coinfection */
        public array $comorbidity = [],
        /** @var array<FHIRReference> indication The indication which this is a contraidication for */
        public array $indication = [],
        /** @var array<FHIRClinicalUseDefinitionContraindicationOtherTherapy> otherTherapy Information about use of the product in relation to other therapies described as part of the contraindication */
        public array $otherTherapy = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

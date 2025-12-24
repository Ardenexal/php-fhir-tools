<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableReference;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRRange;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;

/**
 * @description Specifics for when this is an indication.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ClinicalUseDefinition', elementPath: 'ClinicalUseDefinition.indication', fhirVersion: 'R4B')]
class FHIRClinicalUseDefinitionIndication extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableReference|null diseaseSymptomProcedure The situation that is being documented as an indicaton for this item */
        public ?FHIRCodeableReference $diseaseSymptomProcedure = null,
        /** @var FHIRCodeableReference|null diseaseStatus The status of the disease or symptom for the indication */
        public ?FHIRCodeableReference $diseaseStatus = null,
        /** @var array<FHIRCodeableReference> comorbidity A comorbidity or coinfection as part of the indication */
        public array $comorbidity = [],
        /** @var FHIRCodeableReference|null intendedEffect The intended effect, aim or strategy to be achieved */
        public ?FHIRCodeableReference $intendedEffect = null,
        /** @var FHIRRange|FHIRString|string|null durationX Timing or duration information */
        public FHIRRange|FHIRString|string|null $durationX = null,
        /** @var array<FHIRReference> undesirableEffect An unwanted side effect or negative outcome of the subject of this resource when being used for this indication */
        public array $undesirableEffect = [],
        /** @var array<FHIRClinicalUseDefinitionContraindicationOtherTherapy> otherTherapy The use of the medicinal product in relation to other therapies described as part of the indication */
        public array $otherTherapy = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

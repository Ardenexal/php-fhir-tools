<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductIndication
 *
 * @description Indication for the Medicinal Product.
 */
#[FhirResource(
    type: 'MedicinalProductIndication',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductIndication',
    fhirVersion: 'R4',
)]
class FHIRMedicinalProductIndication extends FHIRDomainResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var FHIRMeta|null meta Metadata about the resource */
        public ?FHIRMeta $meta = null,
        /** @var FHIRUri|null implicitRules A set of rules under which this content was created */
        public ?FHIRUri $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var FHIRNarrative|null text Text summary of the resource, for human interpretation */
        public ?FHIRNarrative $text = null,
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRReference> subject The medication for which this is an indication */
        public array $subject = [],
        /** @var FHIRCodeableConcept|null diseaseSymptomProcedure The disease, symptom or procedure that is the indication for treatment */
        public ?FHIRCodeableConcept $diseaseSymptomProcedure = null,
        /** @var FHIRCodeableConcept|null diseaseStatus The status of the disease or symptom for which the indication applies */
        public ?FHIRCodeableConcept $diseaseStatus = null,
        /** @var array<FHIRCodeableConcept> comorbidity Comorbidity (concurrent condition) or co-infection as part of the indication */
        public array $comorbidity = [],
        /** @var FHIRCodeableConcept|null intendedEffect The intended effect, aim or strategy to be achieved by the indication */
        public ?FHIRCodeableConcept $intendedEffect = null,
        /** @var FHIRQuantity|null duration Timing or duration information as part of the indication */
        public ?FHIRQuantity $duration = null,
        /** @var array<FHIRMedicinalProductIndicationOtherTherapy> otherTherapy Information about the use of the medicinal product in relation to other therapies described as part of the indication */
        public array $otherTherapy = [],
        /** @var array<FHIRReference> undesirableEffect Describe the undesirable effects of the medicinal product */
        public array $undesirableEffect = [],
        /** @var array<FHIRPopulation> population The population group to which this applies */
        public array $population = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

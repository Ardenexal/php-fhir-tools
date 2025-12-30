<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRMeta;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRNarrative;
use Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 *
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductContraindication
 *
 * @description The clinical particulars - indications, contraindications etc. of a medicinal product, including for regulatory purposes.
 */
#[FhirResource(
    type: 'MedicinalProductContraindication',
    version: '4.0.1',
    url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductContraindication',
    fhirVersion: 'R4B',
)]
class FHIRMedicinalProductContraindication extends FHIRDomainResource
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
        /** @var array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<FHIRReference> subject The medication for which this is an indication */
        public array $subject = [],
        /** @var FHIRCodeableConcept|null disease The disease, symptom or procedure for the contraindication */
        public ?FHIRCodeableConcept $disease = null,
        /** @var FHIRCodeableConcept|null diseaseStatus The status of the disease or symptom for the contraindication */
        public ?FHIRCodeableConcept $diseaseStatus = null,
        /** @var array<FHIRCodeableConcept> comorbidity A comorbidity (concurrent condition) or coinfection */
        public array $comorbidity = [],
        /** @var array<FHIRReference> therapeuticIndication Information about the use of the medicinal product in relation to other therapies as part of the indication */
        public array $therapeuticIndication = [],
        /** @var array<FHIRMedicinalProductContraindicationOtherTherapy> otherTherapy Information about the use of the medicinal product in relation to other therapies described as part of the indication */
        public array $otherTherapy = [],
        /** @var array<FHIRPopulation> population The population group to which this applies */
        public array $population = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

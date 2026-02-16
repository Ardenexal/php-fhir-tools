<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Population;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIndication\MedicinalProductIndicationOtherTherapy;

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
class MedicinalProductIndicationResource extends DomainResourceResource
{
    public function __construct(
        /** @var string|null id Logical id of this artifact */
        public ?string $id = null,
        /** @var Meta|null meta Metadata about the resource */
        public ?Meta $meta = null,
        /** @var UriPrimitive|null implicitRules A set of rules under which this content was created */
        public ?UriPrimitive $implicitRules = null,
        /** @var string|null language Language of the resource content */
        public ?string $language = null,
        /** @var Narrative|null text Text summary of the resource, for human interpretation */
        public ?Narrative $text = null,
        /** @var array<ResourceResource> contained Contained, inline Resources */
        public array $contained = [],
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored */
        public array $modifierExtension = [],
        /** @var array<Reference> subject The medication for which this is an indication */
        public array $subject = [],
        /** @var CodeableConcept|null diseaseSymptomProcedure The disease, symptom or procedure that is the indication for treatment */
        public ?CodeableConcept $diseaseSymptomProcedure = null,
        /** @var CodeableConcept|null diseaseStatus The status of the disease or symptom for which the indication applies */
        public ?CodeableConcept $diseaseStatus = null,
        /** @var array<CodeableConcept> comorbidity Comorbidity (concurrent condition) or co-infection as part of the indication */
        public array $comorbidity = [],
        /** @var CodeableConcept|null intendedEffect The intended effect, aim or strategy to be achieved by the indication */
        public ?CodeableConcept $intendedEffect = null,
        /** @var Quantity|null duration Timing or duration information as part of the indication */
        public ?Quantity $duration = null,
        /** @var array<MedicinalProductIndicationOtherTherapy> otherTherapy Information about the use of the medicinal product in relation to other therapies described as part of the indication */
        public array $otherTherapy = [],
        /** @var array<Reference> undesirableEffect Describe the undesirable effects of the medicinal product */
        public array $undesirableEffect = [],
        /** @var array<Population> population The population group to which this applies */
        public array $population = [],
    ) {
        parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
    }
}

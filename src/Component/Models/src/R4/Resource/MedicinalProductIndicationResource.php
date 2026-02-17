<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductIndication
 * @description Indication for the Medicinal Product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicinalProductIndication',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductIndication',
	fhirVersion: 'R4',
)]
class MedicinalProductIndicationResource extends DomainResourceResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Meta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Narrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> subject The medication for which this is an indication */
		public array $subject = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept diseaseSymptomProcedure The disease, symptom or procedure that is the indication for treatment */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $diseaseSymptomProcedure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept diseaseStatus The status of the disease or symptom for which the indication applies */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $diseaseStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> comorbidity Comorbidity (concurrent condition) or co-infection as part of the indication */
		public array $comorbidity = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept intendedEffect The intended effect, aim or strategy to be achieved by the indication */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $intendedEffect = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity duration Timing or duration information as part of the indication */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity $duration = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductIndication\MedicinalProductIndicationOtherTherapy> otherTherapy Information about the use of the medicinal product in relation to other therapies described as part of the indication */
		public array $otherTherapy = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> undesirableEffect Describe the undesirable effects of the medicinal product */
		public array $undesirableEffect = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Population> population The population group to which this applies */
		public array $population = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

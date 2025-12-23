<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductContraindication
 * @description The clinical particulars - indications, contraindications etc. of a medicinal product, including for regulatory purposes.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicinalProductContraindication',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductContraindication',
	fhirVersion: 'R4',
)]
class FHIRMedicinalProductContraindication extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMeta meta Metadata about the resource */
		public ?FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri implicitRules A set of rules under which this content was created */
		public ?FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> subject The medication for which this is an indication */
		public array $subject = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept disease The disease, symptom or procedure for the contraindication */
		public ?FHIRCodeableConcept $disease = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept diseaseStatus The status of the disease or symptom for the contraindication */
		public ?FHIRCodeableConcept $diseaseStatus = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCodeableConcept> comorbidity A comorbidity (concurrent condition) or coinfection */
		public array $comorbidity = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRReference> therapeuticIndication Information about the use of the medicinal product in relation to other therapies as part of the indication */
		public array $therapeuticIndication = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRMedicinalProductContraindicationOtherTherapy> otherTherapy Information about the use of the medicinal product in relation to other therapies described as part of the indication */
		public array $otherTherapy = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRPopulation> population The population group to which this applies */
		public array $population = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

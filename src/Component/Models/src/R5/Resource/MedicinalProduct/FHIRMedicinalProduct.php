<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProduct
 * @description Detailed definition of a medicinal product, typically for uses other than direct patient care (e.g. regulatory use).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicinalProduct',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProduct',
	fhirVersion: 'R5',
)]
class FHIRMedicinalProduct extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> identifier Business identifier for this product. Could be an MPID */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept type Regulatory type, e.g. Investigational or Authorized */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding domain If this medicine applies to human or veterinary uses */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding $domain = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept combinedPharmaceuticalDoseForm The dose form for a single part product, or combined form of a multiple part product */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $combinedPharmaceuticalDoseForm = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept legalStatusOfSupply The legal status of supply of the medicinal product as classified by the regulator */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $legalStatusOfSupply = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept additionalMonitoringIndicator Whether the Medicinal Product is subject to additional monitoring for regulatory reasons */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $additionalMonitoringIndicator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string> specialMeasures Whether the Medicinal Product is subject to special measures for regulatory reasons */
		public array $specialMeasures = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept paediatricUseIndicator If authorised for use in children */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept $paediatricUseIndicator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept> productClassification Allows the product to be classified by various systems */
		public array $productClassification = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRMarketingStatus> marketingStatus Marketing status of the medicinal product, in contrast to marketing authorizaton */
		public array $marketingStatus = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> pharmaceuticalProduct Pharmaceutical aspects of product */
		public array $pharmaceuticalProduct = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> packagedMedicinalProduct Package representation for the product */
		public array $packagedMedicinalProduct = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> attachedDocument Supporting documentation, typically for regulatory submission */
		public array $attachedDocument = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> masterFile A master file for to the medicinal product (e.g. Pharmacovigilance System Master File) */
		public array $masterFile = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> contact A product specific contact, person (in a role), or an organization */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference> clinicalTrial Clinical trials or studies that this product is involved in */
		public array $clinicalTrial = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicinalProductName> name The product's name, including full name and possibly coded parts */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier> crossReference Reference to another product, e.g. for linking authorised to investigational product */
		public array $crossReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicinalProductManufacturingBusinessOperation> manufacturingBusinessOperation An operation applied to the product, for manufacturing or adminsitrative purpose */
		public array $manufacturingBusinessOperation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRMedicinalProductSpecialDesignation> specialDesignation Indicates if the medicinal product has an orphan designation for the treatment of a rare disease */
		public array $specialDesignation = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

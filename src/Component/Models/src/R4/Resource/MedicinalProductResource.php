<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProduct
 * @description Detailed definition of a medicinal product, typically for uses other than direct patient care (e.g. regulatory use).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicinalProduct',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProduct',
	fhirVersion: 'R4',
)]
class MedicinalProductResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business identifier for this product. Could be an MPID */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type Regulatory type, e.g. Investigational or Authorized */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding domain If this medicine applies to human or veterinary uses */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $domain = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept combinedPharmaceuticalDoseForm The dose form for a single part product, or combined form of a multiple part product */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $combinedPharmaceuticalDoseForm = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept legalStatusOfSupply The legal status of supply of the medicinal product as classified by the regulator */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $legalStatusOfSupply = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept additionalMonitoringIndicator Whether the Medicinal Product is subject to additional monitoring for regulatory reasons */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $additionalMonitoringIndicator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string> specialMeasures Whether the Medicinal Product is subject to special measures for regulatory reasons */
		public array $specialMeasures = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept paediatricUseIndicator If authorised for use in children */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $paediatricUseIndicator = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> productClassification Allows the product to be classified by various systems */
		public array $productClassification = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\MarketingStatus> marketingStatus Marketing status of the medicinal product, in contrast to marketing authorizaton */
		public array $marketingStatus = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> pharmaceuticalProduct Pharmaceutical aspects of product */
		public array $pharmaceuticalProduct = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> packagedMedicinalProduct Package representation for the product */
		public array $packagedMedicinalProduct = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> attachedDocument Supporting documentation, typically for regulatory submission */
		public array $attachedDocument = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> masterFile A master file for to the medicinal product (e.g. Pharmacovigilance System Master File) */
		public array $masterFile = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> contact A product specific contact, person (in a role), or an organization */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> clinicalTrial Clinical trials or studies that this product is involved in */
		public array $clinicalTrial = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct\MedicinalProductName> name The product's name, including full name and possibly coded parts */
		public array $name = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> crossReference Reference to another product, e.g. for linking authorised to investigational product */
		public array $crossReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct\MedicinalProductManufacturingBusinessOperation> manufacturingBusinessOperation An operation applied to the product, for manufacturing or adminsitrative purpose */
		public array $manufacturingBusinessOperation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProduct\MedicinalProductSpecialDesignation> specialDesignation Indicates if the medicinal product has an orphan designation for the treatment of a rare disease */
		public array $specialDesignation = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

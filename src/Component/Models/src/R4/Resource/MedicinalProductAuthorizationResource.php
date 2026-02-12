<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Biomedical Research and Regulation)
 * @see http://hl7.org/fhir/StructureDefinition/MedicinalProductAuthorization
 * @description The regulatory authorization of a medicinal product.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(
	type: 'MedicinalProductAuthorization',
	version: '4.0.1',
	url: 'http://hl7.org/fhir/StructureDefinition/MedicinalProductAuthorization',
	fhirVersion: 'R4',
)]
class MedicinalProductAuthorizationResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Business identifier for the marketing authorization, as assigned by a regulator */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference subject The medicinal product that is being authorized */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $subject = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> country The country in which the marketing authorization has been granted */
		public array $country = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> jurisdiction Jurisdiction within a country */
		public array $jurisdiction = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept status The status of the marketing authorization */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive statusDate The date at which the given status has become applicable */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $statusDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive restoreDate The date when a suspended the marketing or the marketing authorization of the product is anticipated to be restored */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $restoreDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period validityPeriod The beginning of the time period in which the marketing authorization is in the specific status shall be specified A complete date consisting of day, month and year shall be specified using the ISO 8601 date format */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $validityPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period dataExclusivityPeriod A period of time after authorization before generic product applicatiosn can be submitted */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $dataExclusivityPeriod = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive dateOfFirstAuthorization The date when the first authorization was granted by a Medicines Regulatory Agency */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $dateOfFirstAuthorization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive internationalBirthDate Date of first marketing authorization for a company's new medicinal product in any country in the World */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive $internationalBirthDate = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept legalBasis The legal framework against which this authorization is granted */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $legalBasis = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductAuthorization\MedicinalProductAuthorizationJurisdictionalAuthorization> jurisdictionalAuthorization Authorization in areas within a country */
		public array $jurisdictionalAuthorization = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference holder Marketing Authorization Holder */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $holder = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference regulator Medicines Regulatory Agency */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $regulator = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\MedicinalProductAuthorization\MedicinalProductAuthorizationProcedure procedure The regulatory procedure for granting or amending a marketing authorization */
		public ?MedicinalProductAuthorization\MedicinalProductAuthorizationProcedure $procedure = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

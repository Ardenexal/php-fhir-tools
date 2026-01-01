<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/InsurancePlan
 * @description Details of a Health Insurance product/plan provided by an organization.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'InsurancePlan', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/InsurancePlan', fhirVersion: 'R4')]
class FHIRInsurancePlan extends FHIRDomainResource
{
	public function __construct(
		/** @var null|string id Logical id of this artifact */
		public ?string $id = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta meta Metadata about the resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRMeta $meta = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri implicitRules A set of rules under which this content was created */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $implicitRules = null,
		/** @var null|string language Language of the resource content */
		public ?string $language = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative text Text summary of the resource, for human interpretation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRNarrative $text = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource> contained Contained, inline Resources */
		public array $contained = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRIdentifier> identifier Business Identifier for Product */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPublicationStatusType status draft | active | retired | unknown */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPublicationStatusType $status = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept> type Kind of product */
		public array $type = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string name Official name */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string> alias Alternate names */
		public array $alias = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod period When the product is available */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRPeriod $period = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference ownedBy Plan issuer */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $ownedBy = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference administeredBy Product administrator */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference $administeredBy = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> coverageArea Where product applies */
		public array $coverageArea = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInsurancePlanContact> contact Contact for the product */
		public array $contact = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> endpoint Technical endpoint */
		public array $endpoint = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference> network What networks are Included */
		public array $network = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInsurancePlanCoverage> coverage Coverage details */
		public array $coverage = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInsurancePlanPlan> plan Plan details */
		public array $plan = [],
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

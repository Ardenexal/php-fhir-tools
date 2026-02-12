<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @author Health Level Seven International (Patient Administration)
 * @see http://hl7.org/fhir/StructureDefinition/Account
 * @description A financial tool for tracking value accrued for a particular purpose.  In the healthcare field, used to track charges for a patient, cost centers, etc.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FhirResource(type: 'Account', version: '4.0.1', url: 'http://hl7.org/fhir/StructureDefinition/Account', fhirVersion: 'R4')]
class AccountResource extends DomainResourceResource
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
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier> identifier Account number */
		public array $identifier = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AccountStatusType status active | inactive | entered-in-error | on-hold | unknown */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AccountStatusType $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type E.g. patient, expense, depreciation */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Human-readable label */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference> subject The entity that caused the expenses */
		public array $subject = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period servicePeriod Transaction window */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $servicePeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Account\AccountCoverage> coverage The party(s) that are responsible for covering the payment of this account, and what order should they be applied to the account */
		public array $coverage = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference owner Entity managing the Account */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $owner = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string description Explanation of purpose/use */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $description = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Account\AccountGuarantor> guarantor The parties ultimately responsible for balancing the Account */
		public array $guarantor = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference partOf Reference to a parent Account */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $partOf = null,
	) {
		parent::__construct($id, $meta, $implicitRules, $language, $text, $contained, $extension, $modifierExtension);
	}
}

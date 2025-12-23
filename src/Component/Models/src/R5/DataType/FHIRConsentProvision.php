<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Consent.provision
 * @description An exception to the base policy of this consent. An exception can be an addition or removal of access permissions.
 */
class FHIRConsentProvision extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod period Timeframe for this provision */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod $period = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConsentProvisionActor> actor Who|what controlled by this provision (or group, by role) */
		public array $actor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> action Actions controlled by this provision */
		public array $action = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding> securityLabel Security Labels that define affected resources */
		public array $securityLabel = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding> purpose Context of activities covered by this provision */
		public array $purpose = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding> documentType e.g. Resource Type, Profile, CDA, etc */
		public array $documentType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCoding> resourceType e.g. Resource Type, Profile, etc */
		public array $resourceType = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> code e.g. LOINC or SNOMED CT code, etc. in the content */
		public array $code = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod dataPeriod Timeframe for data controlled by this provision */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRPeriod $dataPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConsentProvisionData> data Data controlled by this provision */
		public array $data = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression expression A computable expression of the consent */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExpression $expression = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRConsentProvision> provision Nested Exception Provisions */
		public array $provision = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

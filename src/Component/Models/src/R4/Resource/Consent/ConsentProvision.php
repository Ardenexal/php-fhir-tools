<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent;

/**
 * @description An exception to the base policy of this consent. An exception can be an addition or removal of access permissions.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Consent', elementPath: 'Consent.provision', fhirVersion: 'R4')]
class ConsentProvision extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ConsentProvisionTypeType type deny | permit */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ConsentProvisionTypeType $type = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period period Timeframe for this rule */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $period = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent\ConsentProvisionActor> actor Who|what controlled by this rule (or group, by role) */
		public array $actor = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> action Actions controlled by this rule */
		public array $action = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding> securityLabel Security Labels that define affected resources */
		public array $securityLabel = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding> purpose Context of activities covered by this rule */
		public array $purpose = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding> class e.g. Resource Type, Profile, CDA, etc. */
		public array $class = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> code e.g. LOINC or SNOMED CT code, etc. in the content */
		public array $code = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period dataPeriod Timeframe for data controlled by this rule */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Period $dataPeriod = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent\ConsentProvisionData> data Data controlled by this rule */
		public array $data = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Consent\ConsentProvision> provision Nested Exception Rules */
		public array $provision = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

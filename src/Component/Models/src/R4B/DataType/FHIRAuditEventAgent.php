<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element AuditEvent.agent
 * @description An actor taking an active role in the event or activity that is logged.
 */
class FHIRAuditEventAgent extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept type How agent participated */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> role Agent role in the event */
		public array $role = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference who Identifier of who */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $who = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string altId Alternative User identity */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $altId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string name Human friendly name for the agent */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean requestor Whether user is initiator */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $requestor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference location Where */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri> policy Policy that authorized event */
		public array $policy = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding media Type of media */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCoding $media = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAuditEventAgentNetwork network Logical network location for application activity */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAuditEventAgentNetwork $network = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCodeableConcept> purposeOfUse Reason given for this user */
		public array $purposeOfUse = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

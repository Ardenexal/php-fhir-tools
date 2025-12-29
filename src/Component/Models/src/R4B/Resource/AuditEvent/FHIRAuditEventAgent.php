<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

/**
 * @description An actor taking an active role in the event or activity that is logged.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.agent', fhirVersion: 'R4B')]
class FHIRAuditEventAgent extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept type How agent participated */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> role Agent role in the event */
		public array $role = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference who Identifier of who */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $who = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string altId Alternative User identity */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $altId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string name Human friendly name for the agent */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean requestor Whether user is initiator */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $requestor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference location Where */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri> policy Policy that authorized event */
		public array $policy = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding media Type of media */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCoding $media = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAuditEventAgentNetwork network Logical network location for application activity */
		public ?FHIRAuditEventAgentNetwork $network = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRCodeableConcept> purposeOfUse Reason given for this user */
		public array $purposeOfUse = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

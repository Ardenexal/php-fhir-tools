<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent;

/**
 * @description An actor taking an active role in the event or activity that is logged.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.agent', fhirVersion: 'R4')]
class AuditEventAgent extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept type How agent participated */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept $type = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> role Agent role in the event */
		public array $role = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference who Identifier of who */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $who = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string altId Alternative User identity */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $altId = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Human friendly name for the agent */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|bool requestor Whether user is initiator */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?bool $requestor = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference location Where */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $location = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive> policy Policy that authorized event */
		public array $policy = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding media Type of media */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Coding $media = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent\AuditEventAgentNetwork network Logical network location for application activity */
		public ?AuditEventAgentNetwork $network = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept> purposeOfUse Reason given for this user */
		public array $purposeOfUse = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

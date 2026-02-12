<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\AuditEvent;

/**
 * @description Logical network location for application activity, if the activity has a network location.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'AuditEvent', elementPath: 'AuditEvent.agent.network', fhirVersion: 'R4')]
class AuditEventAgentNetwork extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string address Identifier for the network access point of the user device */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $address = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\AuditEventAgentNetworkTypeType type The type of network access point */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\AuditEventAgentNetworkTypeType $type = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

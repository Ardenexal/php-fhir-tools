<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element AuditEvent.agent.network
 * @description Logical network location for application activity, if the activity has a network location.
 */
class FHIRAuditEventAgentNetwork extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string address Identifier for the network access point of the user device */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $address = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAuditEventAgentNetworkTypeType type The type of network access point */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRAuditEventAgentNetworkTypeType $type = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

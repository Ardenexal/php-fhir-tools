<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

/**
 * @description A description of the messaging capabilities of the solution.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.messaging', fhirVersion: 'R4')]
class CapabilityStatementMessaging extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementMessagingEndpoint> endpoint Where messages should be sent */
		public array $endpoint = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive reliableCache Reliable Message Cache Length (min) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UnsignedIntPrimitive $reliableCache = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive documentation Messaging interface behavior details */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\MarkdownPrimitive $documentation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement\CapabilityStatementMessagingSupportedMessage> supportedMessage Messages supported by this system */
		public array $supportedMessage = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

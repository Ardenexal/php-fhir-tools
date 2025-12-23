<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element CapabilityStatement.messaging
 * @description A description of the messaging capabilities of the solution.
 */
class FHIRCapabilityStatementMessaging extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCapabilityStatementMessagingEndpoint> endpoint Where messages should be sent */
		public array $endpoint = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnsignedInt reliableCache Reliable Message Cache Length (min) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUnsignedInt $reliableCache = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown documentation Messaging interface behavior details */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRMarkdown $documentation = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCapabilityStatementMessagingSupportedMessage> supportedMessage Messages supported by this system */
		public array $supportedMessage = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\CapabilityStatement;

/**
 * @description References to message definitions for messages this system can send or receive.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'CapabilityStatement', elementPath: 'CapabilityStatement.messaging.supportedMessage', fhirVersion: 'R4')]
class CapabilityStatementMessagingSupportedMessage extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\EventCapabilityModeType mode sender | receiver */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\EventCapabilityModeType $mode = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive definition Message supported by this system */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\CanonicalPrimitive $definition = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

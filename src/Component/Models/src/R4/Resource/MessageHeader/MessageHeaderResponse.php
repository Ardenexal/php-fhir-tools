<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageHeader;

/**
 * @description Information about the message that this message is a response to.  Only present if this message is a response.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.response', fhirVersion: 'R4')]
class MessageHeaderResponse extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive identifier Id of original message */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\IdPrimitive $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\ResponseTypeType code ok | transient-error | fatal-error */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\ResponseTypeType $code = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference details Specific list of hints/warnings/errors */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $details = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\MessageHeader;

/**
 * @description The destination application which the message is intended for.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.destination', fhirVersion: 'R4')]
class MessageHeaderDestination extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string name Name of system */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference target Particular delivery destination within the destination */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $target = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive endpoint Actual destination address or id */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UrlPrimitive $endpoint = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference receiver Intended "real-world" recipient for the data */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference $receiver = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

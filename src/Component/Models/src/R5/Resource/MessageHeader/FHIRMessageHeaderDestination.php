<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The destination application which the message is intended for.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.destination', fhirVersion: 'R5')]
class FHIRMessageHeaderDestination extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference endpointX Actual destination address or Endpoint resource */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|null $endpointX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string name Name of system */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference target Particular delivery destination within the destination */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $target = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference receiver Intended "real-world" recipient for the data */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $receiver = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

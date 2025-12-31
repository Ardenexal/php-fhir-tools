<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The source application from which this message originated.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'MessageHeader', elementPath: 'MessageHeader.source', fhirVersion: 'R5')]
class FHIRMessageHeaderSource extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference endpointX Actual source address or Endpoint resource */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference|null $endpointX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string name Name of system */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string software Name of software running the system */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $software = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string version Version of software running */
		public \Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactPoint contact Human contact for problems */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRContactPoint $contact = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

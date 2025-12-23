<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element MessageHeader.source
 * @description The source application from which this message originated.
 */
class FHIRMessageHeaderSource extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference endpointX Actual source address or Endpoint resource */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUrl|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference|null $endpointX = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string name Name of system */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $name = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string software Name of software running the system */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $software = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string version Version of software running */
		public \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRString|string|null $version = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactPoint contact Human contact for problems */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRContactPoint $contact = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

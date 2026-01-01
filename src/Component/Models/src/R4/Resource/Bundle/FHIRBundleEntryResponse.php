<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Indicates the results of processing the corresponding 'request' entry in the batch or transaction being responded to or what the results of an operation where when returning history.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.response', fhirVersion: 'R4')]
class FHIRBundleEntryResponse extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string status Status response code (text optional) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri location The location (if the operation returns a location) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string etag The Etag for the resource (if relevant) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $etag = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant lastModified Server's date time modified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant $lastModified = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource outcome OperationOutcome with hints and warnings (for batch/transaction) */
		public ?FHIRResource $outcome = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

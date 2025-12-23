<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

/**
 * @fhir-backbone-element Bundle.entry.response
 * @description Indicates the results of processing the corresponding 'request' entry in the batch or transaction being responded to or what the results of an operation where when returning history.
 */
class FHIRBundleEntryResponse extends \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string status Status response code (text optional) */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $status = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri location The location (if the operation returns a location) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRUri $location = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string etag The Etag for the resource (if relevant) */
		public \Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRString|string|null $etag = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInstant lastModified Server's date time modified */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRInstant $lastModified = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource outcome OperationOutcome with hints and warnings (for batch/transaction) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource $outcome = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

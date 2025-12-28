<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description Additional information about how this entry should be processed as part of a transaction or batch.  For history, it shows how the entry was processed to create the version contained in the entry.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry.request', fhirVersion: 'R4')]
class FHIRBundleEntryRequest extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRHTTPVerbType method GET | HEAD | POST | PUT | DELETE | PATCH */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRHTTPVerbType $method = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri url URL for HTTP equivalent of this entry */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $url = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string ifNoneMatch For managing cache currency */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $ifNoneMatch = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant ifModifiedSince For managing cache currency */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRInstant $ifModifiedSince = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string ifMatch For managing update contention */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $ifMatch = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string ifNoneExist For conditional creates */
		public \Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString|string|null $ifNoneExist = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

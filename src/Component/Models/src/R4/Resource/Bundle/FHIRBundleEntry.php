<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

/**
 * @description An entry in a bundle resource - will either contain a resource or information about a resource (transactions and history only).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry', fhirVersion: 'R4')]
class FHIRBundleEntry extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBundleLink> link Links related to this entry */
		public array $link = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri fullUrl URI for resource (Absolute URL server address or URI for UUID/OID) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRUri $fullUrl = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRResource resource A resource in the bundle */
		public ?FHIRResource $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBundleEntrySearch search Search related information */
		public ?FHIRBundleEntrySearch $search = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBundleEntryRequest request Additional execution information (transaction/batch/history) */
		public ?FHIRBundleEntryRequest $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRBundleEntryResponse response Results of execution (transaction/batch/history) */
		public ?FHIRBundleEntryResponse $response = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

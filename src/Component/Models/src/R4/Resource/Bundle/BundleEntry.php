<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle;

/**
 * @description An entry in a bundle resource - will either contain a resource or information about a resource (transactions and history only).
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Bundle', elementPath: 'Bundle.entry', fhirVersion: 'R4')]
class BundleEntry extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleLink> link Links related to this entry */
		public array $link = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive fullUrl URI for resource (Absolute URL server address or URI for UUID/OID) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive $fullUrl = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource resource A resource in the bundle */
		public ?\Ardenexal\FHIRTools\Component\Models\R4\Resource\ResourceResource $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntrySearch search Search related information */
		public ?BundleEntrySearch $search = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntryRequest request Additional execution information (transaction/batch/history) */
		public ?BundleEntryRequest $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4\Resource\Bundle\BundleEntryResponse response Results of execution (transaction/batch/history) */
		public ?BundleEntryResponse $response = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

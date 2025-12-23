<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element Bundle.entry
 * @description An entry in a bundle resource - will either contain a resource or information about a resource (transactions and history only).
 */
class FHIRBundleEntry extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBundleLink> link Links related to this entry */
		public array $link = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri fullUrl URI for resource (Absolute URL server address or URI for UUID/OID) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRUri $fullUrl = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource resource A resource in the bundle */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRResource $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBundleEntrySearch search Search related information */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBundleEntrySearch $search = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBundleEntryRequest request Additional execution information (transaction/batch/history) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBundleEntryRequest $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBundleEntryResponse response Results of execution (transaction/batch/history) */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBundleEntryResponse $response = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Bundle.entry
 * @description An entry in a bundle resource - will either contain a resource or information about a resource (transactions and history only).
 */
class FHIRBundleEntry extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBundleLink> link Links related to this entry */
		public array $link = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri fullUrl URI for resource (e.g. the absolute URL server address, URI for UUID/OID, etc.) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRUri $fullUrl = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource resource A resource in the bundle */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRResource $resource = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBundleEntrySearch search Search related information */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBundleEntrySearch $search = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBundleEntryRequest request Additional execution information (transaction/batch/history) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBundleEntryRequest $request = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBundleEntryResponse response Results of execution (transaction/batch/history) */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBundleEntryResponse $response = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

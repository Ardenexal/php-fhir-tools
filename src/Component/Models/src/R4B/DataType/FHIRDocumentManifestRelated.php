<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

/**
 * @fhir-backbone-element DocumentManifest.related
 * @description Related identifiers or resources associated with the DocumentManifest.
 */
class FHIRDocumentManifestRelated extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier identifier Identifiers of things that are related */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference ref Related Resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $ref = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

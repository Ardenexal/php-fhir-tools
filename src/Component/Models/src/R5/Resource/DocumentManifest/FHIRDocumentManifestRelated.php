<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Related identifiers or resources associated with the DocumentManifest.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'DocumentManifest', elementPath: 'DocumentManifest.related', fhirVersion: 'R5')]
class FHIRDocumentManifestRelated extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier identifier Identifiers of things that are related */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRIdentifier $identifier = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference ref Related Resource */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference $ref = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element Citation.citedArtifact.classification.whoClassified
 * @description Provenance and copyright of classification.
 */
class FHIRCitationCitedArtifactClassificationWhoClassified extends \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference person Person who created the classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $person = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference organization Organization who created the classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $organization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference publisher The publisher of the classification, not the publisher of the article or artifact being cited */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRReference $publisher = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string classifierCopyright Rights management statement for the classification */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRString|string|null $classifierCopyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean freeToShare Acceptable to re-use the classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRBoolean $freeToShare = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

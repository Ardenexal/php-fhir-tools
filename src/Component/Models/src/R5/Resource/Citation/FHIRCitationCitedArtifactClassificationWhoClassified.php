<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description Provenance and copyright of classification.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'Citation', elementPath: 'Citation.citedArtifact.classification.whoClassified', fhirVersion: 'R4B')]
class FHIRCitationCitedArtifactClassificationWhoClassified extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference person Person who created the classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $person = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference organization Organization who created the classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $organization = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference publisher The publisher of the classification, not the publisher of the article or artifact being cited */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRReference $publisher = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string classifierCopyright Rights management statement for the classification */
		public \Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString|string|null $classifierCopyright = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean freeToShare Acceptable to re-use the classification */
		public ?\Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRBoolean $freeToShare = null,
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

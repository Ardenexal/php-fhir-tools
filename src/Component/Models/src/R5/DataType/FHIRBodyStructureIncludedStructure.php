<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element BodyStructure.includedStructure
 * @description The anatomical location(s) or region(s) of the specimen, lesion, or body structure.
 */
class FHIRBodyStructureIncludedStructure extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept structure Code that represents the included structure */
		#[\Symfony\Component\Validator\Constraints\NotBlank]
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $structure = null,
		/** @var null|\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept laterality Code that represents the included structure laterality */
		public ?\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept $laterality = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBodyStructureIncludedStructureBodyLandmarkOrientation> bodyLandmarkOrientation Landmark relative location */
		public array $bodyLandmarkOrientation = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRReference> spatialReference Cartesian reference for structure */
		public array $spatialReference = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> qualifier Code that represents the included structure qualifier */
		public array $qualifier = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

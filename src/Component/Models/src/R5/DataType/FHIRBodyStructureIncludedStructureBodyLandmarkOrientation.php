<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element BodyStructure.includedStructure.bodyLandmarkOrientation
 * @description Body locations in relation to a specific body landmark (tatoo, scar, other body structure).
 */
class FHIRBodyStructureIncludedStructureBodyLandmarkOrientation extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> landmarkDescription Body ]andmark description */
		public array $landmarkDescription = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> clockFacePosition Clockface orientation */
		public array $clockFacePosition = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBodyStructureIncludedStructureBodyLandmarkOrientationDistanceFromLandmark> distanceFromLandmark Landmark relative location */
		public array $distanceFromLandmark = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableConcept> surfaceOrientation Relative landmark surface orientation */
		public array $surfaceOrientation = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

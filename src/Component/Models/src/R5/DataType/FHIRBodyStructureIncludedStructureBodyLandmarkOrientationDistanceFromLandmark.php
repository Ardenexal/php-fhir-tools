<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

/**
 * @fhir-backbone-element BodyStructure.includedStructure.bodyLandmarkOrientation.distanceFromLandmark
 * @description The distance in centimeters a certain observation is made from a body landmark.
 */
class FHIRBodyStructureIncludedStructureBodyLandmarkOrientationDistanceFromLandmark extends \Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRCodeableReference> device Measurement device */
		public array $device = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\Resource\FHIRQuantity> value Measured distance from body landmark */
		public array $value = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

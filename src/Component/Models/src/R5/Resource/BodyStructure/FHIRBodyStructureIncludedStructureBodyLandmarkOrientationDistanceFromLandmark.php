<?php declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

/**
 * @description The distance in centimeters a certain observation is made from a body landmark.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(
	parentResource: 'BodyStructure',
	elementPath: 'BodyStructure.includedStructure.bodyLandmarkOrientation.distanceFromLandmark',
	fhirVersion: 'R5',
)]
class FHIRBodyStructureIncludedStructureBodyLandmarkOrientationDistanceFromLandmark extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
	public function __construct(
		/** @var null|string id Unique id for inter-element referencing */
		public ?string $id = null,
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> extension Additional content defined by implementations */
		public array $extension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
		public array $modifierExtension = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableReference> device Measurement device */
		public array $device = [],
		/** @var  array<\Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRQuantity> value Measured distance from body landmark */
		public array $value = [],
	) {
		parent::__construct($id, $extension, $modifierExtension);
	}
}

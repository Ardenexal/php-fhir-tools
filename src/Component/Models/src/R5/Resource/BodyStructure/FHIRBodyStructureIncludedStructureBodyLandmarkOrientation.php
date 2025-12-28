<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Body locations in relation to a specific body landmark (tatoo, scar, other body structure).
 */
#[FHIRBackboneElement(
    parentResource: 'BodyStructure',
    elementPath: 'BodyStructure.includedStructure.bodyLandmarkOrientation',
    fhirVersion: 'R5',
)]
class FHIRBodyStructureIncludedStructureBodyLandmarkOrientation extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableConcept> landmarkDescription Body ]andmark description */
        public array $landmarkDescription = [],
        /** @var array<FHIRCodeableConcept> clockFacePosition Clockface orientation */
        public array $clockFacePosition = [],
        /** @var array<FHIRBodyStructureIncludedStructureBodyLandmarkOrientationDistanceFromLandmark> distanceFromLandmark Landmark relative location */
        public array $distanceFromLandmark = [],
        /** @var array<FHIRCodeableConcept> surfaceOrientation Relative landmark surface orientation */
        public array $surfaceOrientation = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

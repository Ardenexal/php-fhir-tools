<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description The distance in centimeters a certain observation is made from a body landmark.
 */
#[FHIRBackboneElement(
    parentResource: 'BodyStructure',
    elementPath: 'BodyStructure.includedStructure.bodyLandmarkOrientation.distanceFromLandmark',
    fhirVersion: 'R5',
)]
class FHIRBodyStructureIncludedStructureBodyLandmarkOrientationDistanceFromLandmark extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRCodeableReference> device Measurement device */
        public array $device = [],
        /** @var array<FHIRQuantity> value Measured distance from body landmark */
        public array $value = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

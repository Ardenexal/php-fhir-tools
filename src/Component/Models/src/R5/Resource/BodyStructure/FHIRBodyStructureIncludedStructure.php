<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRReference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description The anatomical location(s) or region(s) of the specimen, lesion, or body structure.
 */
#[FHIRBackboneElement(parentResource: 'BodyStructure', elementPath: 'BodyStructure.includedStructure', fhirVersion: 'R5')]
class FHIRBodyStructureIncludedStructure extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null structure Code that represents the included structure */
        #[NotBlank]
        public ?FHIRCodeableConcept $structure = null,
        /** @var FHIRCodeableConcept|null laterality Code that represents the included structure laterality */
        public ?FHIRCodeableConcept $laterality = null,
        /** @var array<FHIRBodyStructureIncludedStructureBodyLandmarkOrientation> bodyLandmarkOrientation Landmark relative location */
        public array $bodyLandmarkOrientation = [],
        /** @var array<FHIRReference> spatialReference Cartesian reference for structure */
        public array $spatialReference = [],
        /** @var array<FHIRCodeableConcept> qualifier Code that represents the included structure qualifier */
        public array $qualifier = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

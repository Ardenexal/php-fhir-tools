<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each imaging selection might includes a 3D image region, specified by a region type and a set of 3D coordinates.
 */
#[FHIRBackboneElement(parentResource: 'ImagingSelection', elementPath: 'ImagingSelection.instance.imageRegion3D', fhirVersion: 'R5')]
class FHIRImagingSelectionInstanceImageRegion3D extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRImagingSelection3DGraphicTypeType|null regionType point | multipoint | polyline | polygon | ellipse | ellipsoid */
        #[NotBlank]
        public ?\FHIRImagingSelection3DGraphicTypeType $regionType = null,
        /** @var array<FHIRDecimal> coordinate Specifies the coordinates that define the image region */
        public array $coordinate = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRImagingSelection2DGraphicTypeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRDecimal;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each imaging selection instance or frame list might includes an image region, specified by a region type and a set of 2D coordinates.
 *        If the parent imagingSelection.instance contains a subset element of type frame, the image region applies to all frames in the subset list.
 */
#[FHIRBackboneElement(parentResource: 'ImagingSelection', elementPath: 'ImagingSelection.instance.imageRegion2D', fhirVersion: 'R5')]
class FHIRImagingSelectionInstanceImageRegion2D extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRImagingSelection2DGraphicTypeType|null regionType point | polyline | interpolated | circle | ellipse */
        #[NotBlank]
        public ?FHIRImagingSelection2DGraphicTypeType $regionType = null,
        /** @var array<FHIRDecimal> coordinate Specifies the coordinates that define the image region */
        public array $coordinate = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

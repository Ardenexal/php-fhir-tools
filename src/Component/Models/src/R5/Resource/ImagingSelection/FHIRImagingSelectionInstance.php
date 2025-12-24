<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRCoding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRId;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRUnsignedInt;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each imaging selection includes one or more selected DICOM SOP instances.
 */
#[\Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement(parentResource: 'ImagingSelection', elementPath: 'ImagingSelection.instance', fhirVersion: 'R5')]
class FHIRImagingSelectionInstance extends FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRId|null uid DICOM SOP Instance UID */
        #[NotBlank]
        public ?FHIRId $uid = null,
        /** @var FHIRUnsignedInt|null number DICOM Instance Number */
        public ?FHIRUnsignedInt $number = null,
        /** @var FHIRCoding|null sopClass DICOM SOP Class UID */
        public ?FHIRCoding $sopClass = null,
        /** @var array<FHIRString|string> subset The selected subset of the SOP Instance */
        public array $subset = [],
        /** @var array<FHIRImagingSelectionInstanceImageRegion2D> imageRegion2D A specific 2D region in a DICOM image / frame */
        public array $imageRegion2D = [],
        /** @var array<FHIRImagingSelectionInstanceImageRegion3D> imageRegion3D A specific 3D region in a DICOM frame of reference */
        public array $imageRegion3D = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

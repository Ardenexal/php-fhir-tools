<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ImagingSelection;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ImagingSelection2DGraphicTypeType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Each imaging selection instance or frame list might includes an image region, specified by a region type and a set of 2D coordinates.
 *        If the parent imagingSelection.instance contains a subset element of type frame, the image region applies to all frames in the subset list.
 */
#[FHIRBackboneElement(parentResource: 'ImagingSelection', elementPath: 'ImagingSelection.instance.imageRegion2D', fhirVersion: 'R5')]
class ImagingSelectionInstanceImageRegion2D extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var ImagingSelection2DGraphicTypeType|null regionType point | polyline | interpolated | circle | ellipse */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?ImagingSelection2DGraphicTypeType $regionType = null,
        /** @var array<numeric-string> coordinate Specifies the coordinates that define the image region */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar', isArray: true, isRequired: true)]
        public array $coordinate = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

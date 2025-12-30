<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Imaging Selection2 D Graphic Type
 * URL: http://hl7.org/fhir/ValueSet/imagingselection-2dgraphictype
 * Version: 5.0.0
 * Description: The type of 2D coordinates describing a 2D image region.
 */
enum FHIRImagingSelection2DGraphicType: string
{
    /** POINT */
    case point = 'point';

    /** POLYLINE */
    case polyline = 'polyline';

    /** INTERPOLATED */
    case interpolated = 'interpolated';

    /** CIRCLE */
    case circle = 'circle';

    /** ELLIPSE */
    case ellipse = 'ellipse';
}

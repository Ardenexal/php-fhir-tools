<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Imaging Selection3 D Graphic Type
 * URL: http://hl7.org/fhir/ValueSet/imagingselection-3dgraphictype
 * Version: 5.0.0
 * Description: The type of coordinates describing a 3D image region.
 */
enum FHIRImagingSelection3DGraphicType: string
{
    /** POINT */
    case point = 'point';

    /** MULTIPOINT */
    case multipoint = 'multipoint';

    /** POLYLINE */
    case polyline = 'polyline';

    /** POLYGON */
    case polygon = 'polygon';

    /** ELLIPSE */
    case ellipse = 'ellipse';

    /** ELLIPSOID */
    case ellipsoid = 'ellipsoid';
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: GroupMeasure
 * URL: http://hl7.org/fhir/ValueSet/group-measure
 * Version: 4.3.0
 * Description: Possible group measure aggregates (E.g. Mean, Median).
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/group-measure', version: '4.3.0')]
enum GroupMeasure: string
{
    /** Mean */
    case mean = 'mean';

    /** Median */
    case median = 'median';

    /** Mean of Study Means */
    case meanofstudymeans = 'mean-of-mean';

    /** Mean of Study Medins */
    case meanofstudymedins = 'mean-of-median';

    /** Median of Study Means */
    case medianofstudymeans = 'median-of-mean';

    /** Median of Study Medians */
    case medianofstudymedians = 'median-of-median';
}

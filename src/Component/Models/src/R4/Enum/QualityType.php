<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: qualityType
 * URL: http://hl7.org/fhir/ValueSet/quality-type
 * Version: 4.0.1
 * Description: Type for quality report.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/quality-type', version: '4.0.1')]
enum QualityType: string
{
    /** INDEL Comparison */
    case indelcomparison = 'indel';

    /** SNP Comparison */
    case snpcomparison = 'snp';

    /** UNKNOWN Comparison */
    case unknowncomparison = 'unknown';
}

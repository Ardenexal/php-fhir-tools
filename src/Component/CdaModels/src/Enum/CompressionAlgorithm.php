<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDACompressionAlgorithm
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDACompressionAlgorithm
 * Version: 2.0.2-sd
 * Description: Type of compression algorithm used - limited to 4 concepts from original CDA definition
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/CDACompressionAlgorithm', version: '2.0.2-sd')]
enum CompressionAlgorithm: string
{
    /** DF */
    case df = 'DF';

    /** GZ */
    case gz = 'GZ';

    /** ZL */
    case zl = 'ZL';

    /** Z */
    case z = 'Z';
}

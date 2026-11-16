<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDAActCode
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDAActCode
 * Version: 1.0.1
 * Description: A code specifying the particular kind of Act that the Act-instance represents within its class.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/CDAActCode', version: '1.0.1')]
enum ActCode
{
}

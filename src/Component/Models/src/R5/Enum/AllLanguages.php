<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: All Languages
 * URL: http://hl7.org/fhir/ValueSet/all-languages
 * Version: 5.0.0
 * Description: This value set includes all possible codes from BCP-47 (see http://tools.ietf.org/html/bcp47)
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/all-languages', version: '5.0.0')]
enum AllLanguages
{
}

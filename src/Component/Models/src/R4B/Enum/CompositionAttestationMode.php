<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CompositionAttestationMode
 * URL: http://hl7.org/fhir/ValueSet/composition-attestation-mode
 * Version: 4.3.0
 * Description: The way in which a person authenticated a composition.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/fhir/ValueSet/composition-attestation-mode', version: '4.3.0')]
enum CompositionAttestationMode: string
{
    /** Personal */
    case personal = 'personal';

    /** Professional */
    case professional = 'professional';

    /** Legal */
    case legal = 'legal';

    /** Official */
    case official = 'official';
}

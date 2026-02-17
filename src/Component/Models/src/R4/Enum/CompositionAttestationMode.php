<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Enum;

/**
 * ValueSet: CompositionAttestationMode
 * URL: http://hl7.org/fhir/ValueSet/composition-attestation-mode
 * Version: 4.0.1
 * Description: The way in which a person authenticated a composition.
 */
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

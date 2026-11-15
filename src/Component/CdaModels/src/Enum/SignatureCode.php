<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDASignatureCode
 * URL: http://hl7.org/cda/stds/core/ValueSet/CDASignatureCode
 * Version: 2.0.2-sd
 * Description: A set of codes specifying whether and how the participant has attested his participation through a signature - limited to values allowed in original CDA definition.
 *
 * **Note:** CDA Release One represented either an intended (`X`) or actual (`S`) authenticator. CDA Release Two only represents an actual authenticator, so has deprecated the value of `X`.
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/CDASignatureCode', version: '2.0.2-sd')]
enum SignatureCode: string
{
    /** S */
    case s = 'S';

    /** X */
    case x = 'X';
}

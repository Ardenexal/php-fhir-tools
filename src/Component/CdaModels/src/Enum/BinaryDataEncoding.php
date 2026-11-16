<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetSource;

/**
 * ValueSet: CDABinaryDataEncoding
 * URL: http://hl7.org/cda/stds/core/ValueSet/BinaryDataEncoding
 * Version: 2.0.2-sd
 * Description: Identifies the representation of binary data in a text field
 */
#[FHIRValueSetSource(url: 'http://hl7.org/cda/stds/core/ValueSet/BinaryDataEncoding', version: '2.0.2-sd')]
enum BinaryDataEncoding: string
{
    /** Base64-encoded text */
    case base64_encodedtext = 'B64';

    /** Plain text */
    case plaintext = 'TXT';
}

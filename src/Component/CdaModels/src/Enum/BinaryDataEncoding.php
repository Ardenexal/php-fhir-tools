<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\Enum;

/**
 * ValueSet: CDABinaryDataEncoding
 * URL: http://hl7.org/cda/stds/core/ValueSet/BinaryDataEncoding
 * Version: 2.0.2-sd
 * Description: Identifies the representation of binary data in a text field
 */
enum BinaryDataEncoding: string
{
    /** Base64-encoded text */
    case base64_encodedtext = 'B64';

    /** Plain text */
    case plaintext = 'TXT';
}

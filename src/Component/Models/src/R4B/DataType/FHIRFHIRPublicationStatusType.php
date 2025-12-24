<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRPublicationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRPublicationStatus
 *
 * @description Code type wrapper for FHIRPublicationStatus enum
 */
class FHIRFHIRPublicationStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRPublicationStatus|string|null $value The code value */
        public FHIRFHIRPublicationStatus|string|null $value = null,
    ) {
    }
}

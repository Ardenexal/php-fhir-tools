<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRPublicationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRPublicationStatus
 *
 * @description Code type wrapper for FHIRPublicationStatus enum
 */
class FHIRPublicationStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIRPublicationStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIREpisodeOfCareStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\FHIRCode;

/**
 * @fhir-code-type FHIREpisodeOfCareStatus
 *
 * @description Code type wrapper for FHIREpisodeOfCareStatus enum
 */
class FHIRFHIREpisodeOfCareStatusType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIREpisodeOfCareStatus|string|null $value The code value */
        public FHIRFHIREpisodeOfCareStatus|string|null $value = null,
    ) {
    }
}

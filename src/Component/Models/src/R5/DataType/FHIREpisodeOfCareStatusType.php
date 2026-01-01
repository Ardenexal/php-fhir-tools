<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIREpisodeOfCareStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIREpisodeOfCareStatus
 *
 * @description Code type wrapper for FHIREpisodeOfCareStatus enum
 */
class FHIREpisodeOfCareStatusType extends FHIRCode
{
    public function __construct(
        /** @param FHIREpisodeOfCareStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

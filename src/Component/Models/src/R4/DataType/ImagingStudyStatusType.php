<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ImagingStudyStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ImagingStudyStatus
 *
 * @description Code type wrapper for ImagingStudyStatus enum
 */
class ImagingStudyStatusType extends CodePrimitive
{
    public function __construct(
        /** @param ImagingStudyStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

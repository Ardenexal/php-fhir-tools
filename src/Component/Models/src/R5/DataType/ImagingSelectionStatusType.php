<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\ImagingSelectionStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type ImagingSelectionStatus
 *
 * @description Code type wrapper for ImagingSelectionStatus enum
 */
class ImagingSelectionStatusType extends CodePrimitive
{
    public function __construct(
        /** @param ImagingSelectionStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

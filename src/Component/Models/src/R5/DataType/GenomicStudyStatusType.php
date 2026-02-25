<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\GenomicStudyStatus;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type GenomicStudyStatus
 *
 * @description Code type wrapper for GenomicStudyStatus enum
 */
class GenomicStudyStatusType extends CodePrimitive
{
    public function __construct(
        /** @param GenomicStudyStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

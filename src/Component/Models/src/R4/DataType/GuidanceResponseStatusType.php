<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\GuidanceResponseStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type GuidanceResponseStatus
 *
 * @description Code type wrapper for GuidanceResponseStatus enum
 */
class GuidanceResponseStatusType extends CodePrimitive
{
    public function __construct(
        /** @param GuidanceResponseStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

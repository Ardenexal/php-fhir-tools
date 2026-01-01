<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRCriteriaNotExistsBehavior;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRCriteriaNotExistsBehavior
 *
 * @description Code type wrapper for FHIRCriteriaNotExistsBehavior enum
 */
class FHIRCriteriaNotExistsBehaviorType extends FHIRCode
{
    public function __construct(
        /** @param FHIRCriteriaNotExistsBehavior|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

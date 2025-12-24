<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRCriteriaNotExistsBehavior;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRCriteriaNotExistsBehavior
 *
 * @description Code type wrapper for FHIRCriteriaNotExistsBehavior enum
 */
class FHIRFHIRCriteriaNotExistsBehaviorType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRCriteriaNotExistsBehavior|string|null $value The code value */
        public FHIRFHIRCriteriaNotExistsBehavior|string|null $value = null,
    ) {
    }
}

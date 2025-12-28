<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRFHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSlicingRules
 *
 * @description Code type wrapper for FHIRSlicingRules enum
 */
class FHIRFHIRSlicingRulesType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRSlicingRules|string|null $value The code value */
        public FHIRFHIRSlicingRules|string|null $value = null,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRSlicingRules
 *
 * @description Code type wrapper for FHIRSlicingRules enum
 */
class FHIRSlicingRulesType extends FHIRCode
{
    public function __construct(
        /** @param FHIRSlicingRules|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\SlicingRules;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type SlicingRules
 *
 * @description Code type wrapper for SlicingRules enum
 */
class SlicingRulesType extends CodePrimitive
{
    public function __construct(
        /** @param SlicingRules|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

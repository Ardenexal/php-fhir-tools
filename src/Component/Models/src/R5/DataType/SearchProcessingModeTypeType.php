<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\SearchProcessingModeType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type SearchProcessingModeType
 *
 * @description Code type wrapper for SearchProcessingModeType enum
 */
class SearchProcessingModeTypeType extends CodePrimitive
{
    public function __construct(
        /** @param SearchProcessingModeType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

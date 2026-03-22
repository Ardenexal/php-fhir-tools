<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\MessageSignificanceCategory;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type MessageSignificanceCategory
 *
 * @description Code type wrapper for MessageSignificanceCategory enum
 */
class MessageSignificanceCategoryType extends CodePrimitive
{
    public function __construct(
        /** @param MessageSignificanceCategory|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

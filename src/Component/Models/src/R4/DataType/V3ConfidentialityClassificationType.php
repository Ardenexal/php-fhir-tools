<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\V3ConfidentialityClassification;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type V3ConfidentialityClassification
 *
 * @description Code type wrapper for V3ConfidentialityClassification enum
 */
class V3ConfidentialityClassificationType extends CodePrimitive
{
    public function __construct(
        /** @param V3ConfidentialityClassification|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

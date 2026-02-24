<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ConsentDataMeaning;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ConsentDataMeaning
 *
 * @description Code type wrapper for ConsentDataMeaning enum
 */
class ConsentDataMeaningType extends CodePrimitive
{
    public function __construct(
        /** @param ConsentDataMeaning|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

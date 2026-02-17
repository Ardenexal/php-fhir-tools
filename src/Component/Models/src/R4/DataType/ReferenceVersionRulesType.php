<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ReferenceVersionRules;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ReferenceVersionRules
 *
 * @description Code type wrapper for ReferenceVersionRules enum
 */
class ReferenceVersionRulesType extends CodePrimitive
{
    public function __construct(
        /** @param ReferenceVersionRules|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

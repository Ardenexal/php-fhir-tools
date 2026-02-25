<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\IANATimezones;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type IANATimezones
 *
 * @description Code type wrapper for IANATimezones enum
 */
class IANATimezonesType extends CodePrimitive
{
    public function __construct(
        /** @param IANATimezones|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

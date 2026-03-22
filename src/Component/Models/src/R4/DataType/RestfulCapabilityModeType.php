<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\RestfulCapabilityMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type RestfulCapabilityMode
 *
 * @description Code type wrapper for RestfulCapabilityMode enum
 */
class RestfulCapabilityModeType extends CodePrimitive
{
    public function __construct(
        /** @param RestfulCapabilityMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

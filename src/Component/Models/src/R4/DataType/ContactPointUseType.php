<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\ContactPointUse;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type ContactPointUse
 *
 * @description Code type wrapper for ContactPointUse enum
 */
class ContactPointUseType extends CodePrimitive
{
    public function __construct(
        /** @param ContactPointUse|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

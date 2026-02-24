<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\ContactPointSystem;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\CodePrimitive;

/**
 * @fhir-code-type ContactPointSystem
 *
 * @description Code type wrapper for ContactPointSystem enum
 */
class ContactPointSystemType extends CodePrimitive
{
    public function __construct(
        /** @param ContactPointSystem|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

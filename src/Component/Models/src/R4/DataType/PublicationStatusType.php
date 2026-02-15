<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\PublicationStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type PublicationStatus
 *
 * @description Code type wrapper for PublicationStatus enum
 */
class PublicationStatusType extends CodePrimitive
{
    public function __construct(
        /** @param PublicationStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

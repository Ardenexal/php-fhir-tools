<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\MessageheaderResponseRequest;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type MessageheaderResponseRequest
 *
 * @description Code type wrapper for MessageheaderResponseRequest enum
 */
class MessageheaderResponseRequestType extends CodePrimitive
{
    public function __construct(
        /** @param MessageheaderResponseRequest|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

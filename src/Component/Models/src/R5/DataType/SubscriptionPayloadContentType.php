<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\SubscriptionPayloadContent;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type SubscriptionPayloadContent
 *
 * @description Code type wrapper for SubscriptionPayloadContent enum
 */
class SubscriptionPayloadContentType extends CodePrimitive
{
    public function __construct(
        /** @param SubscriptionPayloadContent|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

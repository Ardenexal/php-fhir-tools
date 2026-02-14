<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\DocumentReferenceStatus;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type DocumentReferenceStatus
 *
 * @description Code type wrapper for DocumentReferenceStatus enum
 */
class DocumentReferenceStatusType extends CodePrimitive
{
    public function __construct(
        /** @param DocumentReferenceStatus|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

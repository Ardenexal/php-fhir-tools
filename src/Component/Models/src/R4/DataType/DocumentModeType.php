<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\DocumentMode;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;

/**
 * @fhir-code-type DocumentMode
 *
 * @description Code type wrapper for DocumentMode enum
 */
class DocumentModeType extends CodePrimitive
{
    public function __construct(
        /** @param DocumentMode|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

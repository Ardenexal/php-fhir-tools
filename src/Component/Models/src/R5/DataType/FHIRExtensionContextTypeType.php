<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRExtensionContextType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRExtensionContextType
 *
 * @description Code type wrapper for FHIRExtensionContextType enum
 */
class FHIRExtensionContextTypeType extends FHIRCode
{
    public function __construct(
        /** @param FHIRExtensionContextType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

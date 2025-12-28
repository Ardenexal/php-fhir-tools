<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4\Enum\FHIRRepositoryType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRRepositoryType
 *
 * @description Code type wrapper for FHIRRepositoryType enum
 */
class FHIRRepositoryTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRRepositoryType|string|null $value The code value */
        public FHIRRepositoryType|string|null $value = null,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRClinicalUseDefinitionType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRClinicalUseDefinitionType
 *
 * @description Code type wrapper for FHIRClinicalUseDefinitionType enum
 */
class FHIRClinicalUseDefinitionTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRClinicalUseDefinitionType|string|null $value The code value */
        public FHIRClinicalUseDefinitionType|string|null $value = null,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\Models\R4B\Enum\FHIRFHIRClinicalUseDefinitionType;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRCode;

/**
 * @fhir-code-type FHIRClinicalUseDefinitionType
 *
 * @description Code type wrapper for FHIRClinicalUseDefinitionType enum
 */
class FHIRFHIRClinicalUseDefinitionTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRClinicalUseDefinitionType|string|null $value The code value */
        public FHIRFHIRClinicalUseDefinitionType|string|null $value = null,
    ) {
    }
}

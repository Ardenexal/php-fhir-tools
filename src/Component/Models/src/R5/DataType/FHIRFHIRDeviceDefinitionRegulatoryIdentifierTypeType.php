<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRFHIRDeviceDefinitionRegulatoryIdentifierType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceDefinitionRegulatoryIdentifierType
 *
 * @description Code type wrapper for FHIRDeviceDefinitionRegulatoryIdentifierType enum
 */
class FHIRFHIRDeviceDefinitionRegulatoryIdentifierTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRFHIRDeviceDefinitionRegulatoryIdentifierType|string|null $value The code value */
        public FHIRFHIRDeviceDefinitionRegulatoryIdentifierType|string|null $value = null,
    ) {
    }
}

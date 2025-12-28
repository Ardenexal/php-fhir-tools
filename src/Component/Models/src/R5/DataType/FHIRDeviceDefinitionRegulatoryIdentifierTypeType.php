<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\FHIRDeviceDefinitionRegulatoryIdentifierType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\FHIRCode;

/**
 * @fhir-code-type FHIRDeviceDefinitionRegulatoryIdentifierType
 *
 * @description Code type wrapper for FHIRDeviceDefinitionRegulatoryIdentifierType enum
 */
class FHIRDeviceDefinitionRegulatoryIdentifierTypeType extends FHIRCode
{
    public function __construct(
        /** @var FHIRDeviceDefinitionRegulatoryIdentifierType|string|null $value The code value */
        public FHIRDeviceDefinitionRegulatoryIdentifierType|string|null $value = null,
    ) {
    }
}

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
        /** @param FHIRDeviceDefinitionRegulatoryIdentifierType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

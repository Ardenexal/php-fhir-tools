<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Models\R5\Enum\DeviceDefinitionRegulatoryIdentifierType;
use Ardenexal\FHIRTools\Component\Models\R5\Primitive\CodePrimitive;

/**
 * @fhir-code-type DeviceDefinitionRegulatoryIdentifierType
 *
 * @description Code type wrapper for DeviceDefinitionRegulatoryIdentifierType enum
 */
class DeviceDefinitionRegulatoryIdentifierTypeType extends CodePrimitive
{
    public function __construct(
        /** @param DeviceDefinitionRegulatoryIdentifierType|string|null $value The code value (enum or string) */
        ?string $value = null,
    ) {
        parent::__construct(value: $value);
    }
}

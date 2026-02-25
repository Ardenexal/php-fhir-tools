<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Enum;

/**
 * ValueSet: Device Definition Regulatory Identifier Type
 * URL: http://hl7.org/fhir/ValueSet/devicedefinition-regulatory-identifier-type
 * Version: 5.0.0
 * Description: Regulatory Identifier type
 */
enum DeviceDefinitionRegulatoryIdentifierType: string
{
    /** Basic */
    case basic = 'basic';

    /** Master */
    case master = 'master';

    /** License */
    case license = 'license';
}

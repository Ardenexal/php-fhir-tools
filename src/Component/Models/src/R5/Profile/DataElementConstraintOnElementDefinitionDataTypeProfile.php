<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ElementDefinition;

/**
 * @author Health Level Seven International (Orders and Observations)
 *
 * @see http://hl7.org/fhir/StructureDefinition/elementdefinition-de
 *
 * @description Identifies how the ElementDefinition data type is used when it appears within a data element
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/elementdefinition-de',
    baseType: 'ElementDefinition',
    fhirVersion: 'R5',
)]
class DataElementConstraintOnElementDefinitionDataTypeProfile extends ElementDefinition
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/elementdefinition-de';
}

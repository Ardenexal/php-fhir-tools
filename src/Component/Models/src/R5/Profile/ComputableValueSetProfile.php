<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/computablevalueset
 *
 * @description Defines a computable value set as one that SHALL have an expression-based definition (i.e. a value set defined intensionally using expressions of the code systems involved) and MAY have an expansion included. The expression-based definition SHALL be represented in only one of three ways; using the compose element, using the expression extension, or using the rules-text extension to provide a step-by-step process for expanding the value set definition
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/computablevalueset', baseType: 'ValueSet', fhirVersion: 'R5')]
class ComputableValueSetProfile extends ShareableValueSetProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/computablevalueset';
}

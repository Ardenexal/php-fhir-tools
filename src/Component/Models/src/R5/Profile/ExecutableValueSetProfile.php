<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/executablevalueset
 *
 * @description Defines an executable value set as one that SHALL have an expansion included, as well as a usage warning indicating the expansion is a point-in-time snapshot and must be maintained over time for production usage. The value set expansion specifies the timestamp when the expansion was produced, SHOULD contain the parameters used for the expansion, and SHALL contain the codes that are obtained by evaluating the value set definition. If this is ONLY an executable value set, a computable definition of the value set must be obtained to compute the updated expansion.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/executablevalueset', baseType: 'ValueSet', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'extension',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'extension.value[x]',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'extension.value[x]',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: [
        'value' => 'This value set contains a point-in-time expansion enumerating the codes that meet the value set intent. As new versions of the code systems used by the value set are released, the contents of this expansion will need to be updated to incorporate newly defined codes that meet the value set intent. Before, and periodically during production use, the value set expansion contents SHOULD be updated.',
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'expansion',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'expansion.contains.code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'],
)]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'extension.value[x]', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.identifier', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.timestamp', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.total', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.offset', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.parameter', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.contains', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.contains.system', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.contains.abstract', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.contains.inactive', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.contains.version', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.contains.code', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.contains.display', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.contains.property', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.contains.property.code', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.contains.property.value[x]', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
#[FHIRProfileMustSupport(path: 'expansion.contains.contains', groups: ['http://hl7.org/fhir/StructureDefinition/executablevalueset'])]
class ExecutableValueSetProfile extends ShareableValueSetProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/executablevalueset';
}

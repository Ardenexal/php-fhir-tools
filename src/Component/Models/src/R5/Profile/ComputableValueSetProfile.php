<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/computablevalueset
 *
 * @description Defines a computable value set as one that SHALL have an expression-based definition (i.e. a value set defined intensionally using expressions of the code systems involved) and MAY have an expansion included. The expression-based definition SHALL be represented in only one of three ways; using the compose element, using the expression extension, or using the rules-text extension to provide a step-by-step process for expanding the value set definition
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/computablevalueset', baseType: 'ValueSet', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'extension',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'extension',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'extension',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'compose.include.filter.property',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'compose.include.filter.op',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'],
)]
#[FHIRProfileConstraint(
    path: 'compose.include.filter.value',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'],
)]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'immutable', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.lockedDate', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.inactive', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.include', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.include.system', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.include.version', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.include.concept', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.include.filter', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.include.filter.property', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.include.filter.op', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.include.filter.value', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.include.valueSet', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.exclude', groups: ['http://hl7.org/fhir/StructureDefinition/computablevalueset'])]
class ComputableValueSetProfile extends ShareableValueSetProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/computablevalueset';
}

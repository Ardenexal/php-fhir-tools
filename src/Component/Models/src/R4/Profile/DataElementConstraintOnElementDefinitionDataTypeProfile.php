<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\ElementDefinition;

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
    fhirVersion: 'R4',
)]
#[FHIRProfileConstraint(
    path: 'extension',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/elementdefinition-de'],
)]
#[FHIRProfileConstraint(
    path: 'representation',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/elementdefinition-de'],
)]
#[FHIRProfileConstraint(
    path: 'slicing',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/elementdefinition-de'],
)]
#[FHIRProfileConstraint(
    path: 'short',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/elementdefinition-de'],
)]
#[FHIRProfileConstraint(
    path: 'contentReference',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/elementdefinition-de'],
)]
#[FHIRProfileConstraint(
    path: 'type.profile',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/elementdefinition-de'],
)]
#[FHIRProfileConstraint(
    path: 'type.aggregation',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/elementdefinition-de'],
)]
#[FHIRProfileConstraint(
    path: 'fixed[x]',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/elementdefinition-de'],
)]
#[FHIRProfileConstraint(
    path: 'pattern[x]',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/elementdefinition-de'],
)]
#[FHIRProfileConstraint(
    path: 'isModifier',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/elementdefinition-de'],
)]
#[FHIRProfileConstraint(
    path: 'isSummary',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/elementdefinition-de'],
)]
class DataElementConstraintOnElementDefinitionDataTypeProfile extends ElementDefinition
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/elementdefinition-de';
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/fhirpathlibrary
 *
 * @description Represents a computable/executable FHIRPath logic library
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/fhirpathlibrary', baseType: 'Library', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'content',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'],
)]
#[FHIRProfileConstraint(
    path: 'content.id',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'],
)]
#[FHIRProfileConstraint(
    path: 'content.contentType',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'],
)]
#[FHIRProfileConstraint(
    path: 'content.contentType',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'text/fhirpath'],
    groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'],
)]
#[FHIRProfileConstraint(
    path: 'content.data',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'],
)]
#[FHIRProfileMustSupport(path: 'content', groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'])]
#[FHIRProfileMustSupport(path: 'content', groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'])]
#[FHIRProfileMustSupport(path: 'content.id', groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'])]
#[FHIRProfileMustSupport(path: 'content.contentType', groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'])]
#[FHIRProfileMustSupport(path: 'content.data', groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'])]
#[FHIRSlicingRules(property: 'content', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'])]
#[FHIRSliceConstraint(
    property: 'content',
    sliceName: 'fhirPathContent',
    min: 1,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'contentType',
    groups: ['http://hl7.org/fhir/StructureDefinition/fhirpathlibrary'],
    orderedIndex: 0,
    discriminatorValue: 'text/fhirpath',
)]
class FHIRPathLibraryProfile extends LogicLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/fhirpathlibrary';
}

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
 * @see http://hl7.org/fhir/StructureDefinition/cqllibrary
 *
 * @description Represents a computable CQL logic library
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/cqllibrary', baseType: 'Library', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'content',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'],
)]
#[FHIRProfileConstraint(
    path: 'content.contentType',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'],
)]
#[FHIRProfileConstraint(
    path: 'content.contentType',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'text/cql'],
    groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'],
)]
#[FHIRProfileConstraint(
    path: 'content.data',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'],
)]
#[FHIRProfileMustSupport(path: 'content', groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'])]
#[FHIRProfileMustSupport(path: 'content', groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'])]
#[FHIRProfileMustSupport(path: 'content.contentType', groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'])]
#[FHIRProfileMustSupport(path: 'content.data', groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'])]
#[FHIRSlicingRules(property: 'content', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'])]
#[FHIRSliceConstraint(
    property: 'content',
    sliceName: 'cqlContent',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'contentType',
    groups: ['http://hl7.org/fhir/StructureDefinition/cqllibrary'],
    orderedIndex: 0,
    discriminatorValue: 'text/cql',
)]
class CQLLibraryProfile extends LogicLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/cqllibrary';
}

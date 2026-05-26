<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\MeasureResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareablemeasure
 *
 * @description Enforces the minimum information set for the measure metadata required by HL7 and other organizations that share and publish measures
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareablemeasure', baseType: 'Measure', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'url',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'],
)]
#[FHIRProfileConstraint(
    path: 'version',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'],
)]
#[FHIRProfileConstraint(
    path: 'name',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'],
)]
#[FHIRProfileConstraint(
    path: 'title',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'],
)]
#[FHIRProfileConstraint(
    path: 'experimental',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'],
)]
#[FHIRProfileConstraint(
    path: 'date',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'],
)]
#[FHIRProfileConstraint(
    path: 'publisher',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'],
)]
#[FHIRProfileConstraint(
    path: 'description',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'],
)]
#[FHIRProfileMustSupport(path: 'identifier', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'title', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'date', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'contact', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'useContext', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'jurisdiction', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
class ShareableMeasureProfile extends MeasureResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareablemeasure';
}

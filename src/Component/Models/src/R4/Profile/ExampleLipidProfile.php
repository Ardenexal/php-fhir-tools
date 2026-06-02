<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DiagnosticReportResource;

/**
 * @author Grahame Grieve
 *
 * @see http://hl7.org/fhir/StructureDefinition/lipidprofile
 *
 * @description Lipid Lab Report
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/lipidprofile', baseType: 'DiagnosticReport', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
)]
#[FHIRProfileConstraint(
    path: 'result',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 3, 'max' => 4],
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
)]
#[FHIRProfileConstraint(
    path: 'result',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
)]
#[FHIRProfileConstraint(
    path: 'result',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
)]
#[FHIRProfileConstraint(
    path: 'result',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
)]
#[FHIRProfileConstraint(
    path: 'result',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
)]
#[FHIRProfileConstraint(
    path: 'conclusion',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
)]
#[FHIRProfileConstraint(
    path: 'conclusionCode',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
)]
#[FHIRProfileMustSupport(path: 'result', groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'])]
#[FHIRProfileMustSupport(path: 'result', groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'])]
#[FHIRProfileMustSupport(path: 'result', groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'])]
#[FHIRProfileMustSupport(path: 'result', groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'])]
#[FHIRProfileMustSupport(path: 'conclusion', groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'])]
#[FHIRSlicingRules(property: 'result', rules: 'closed', groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'])]
#[FHIRSliceConstraint(
    property: 'result',
    sliceName: 'Cholesterol',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'resolve().code',
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
    orderedIndex: 0,
)]
#[FHIRSliceConstraint(
    property: 'result',
    sliceName: 'Triglyceride',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'resolve().code',
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
    orderedIndex: 1,
)]
#[FHIRSliceConstraint(
    property: 'result',
    sliceName: 'HDLCholesterol',
    min: 1,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'resolve().code',
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
    orderedIndex: 2,
)]
#[FHIRSliceConstraint(
    property: 'result',
    sliceName: 'LDLCholesterol',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'resolve().code',
    groups: ['http://hl7.org/fhir/StructureDefinition/lipidprofile'],
    orderedIndex: 3,
)]
class ExampleLipidProfile extends DiagnosticReportResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/lipidprofile';
}

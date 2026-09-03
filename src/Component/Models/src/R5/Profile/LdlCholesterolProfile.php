<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\ObservationResource;

/**
 * @author Grahame Grieve
 *
 * @see http://hl7.org/fhir/StructureDefinition/ldlcholesterol
 *
 * @description LDL Cholesterol Result
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/ldlcholesterol', baseType: 'Observation', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'],
)]
#[FHIRProfileConstraint(
    path: 'interpretation',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange.low',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange.high',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange.type',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange.appliesTo',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange.age',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'],
)]
#[FHIRProfileConstraint(
    path: 'hasMember',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'],
)]
#[FHIRProfileConstraint(
    path: 'derivedFrom',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'],
)]
#[FHIRProfileMustSupport(path: 'code', groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'])]
#[FHIRProfileMustSupport(path: 'valueQuantity', groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'])]
#[FHIRProfileMustSupport(path: 'interpretation', groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'])]
#[FHIRProfileMustSupport(path: 'note', groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'])]
#[FHIRProfileMustSupport(path: 'referenceRange', groups: ['http://hl7.org/fhir/StructureDefinition/ldlcholesterol'])]
class LdlCholesterolProfile extends ObservationResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/ldlcholesterol';
}

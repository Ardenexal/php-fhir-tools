<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ObservationResource;

/**
 * @author Grahame Grieve
 *
 * @see http://hl7.org/fhir/StructureDefinition/triglyceride
 *
 * @description Triglyceride Result
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/triglyceride', baseType: 'Observation', fhirVersion: 'R4B')]
#[FHIRProfileConstraint(
    path: 'code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileConstraint(
    path: 'code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
    options: [
        'pattern' => [
            'coding' => [
                [
                    'system'  => 'http://loinc.org',
                    'code'    => '35217-9',
                    'display' => "Triglyceride [Moles/\u{200B}volume] in Serum or Plasma",
                ],
            ],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileConstraint(
    path: 'valueQuantity',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileConstraint(
    path: 'interpretation',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange.low',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange.high',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange.type',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange.appliesTo',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange.age',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileConstraint(
    path: 'hasMember',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileConstraint(
    path: 'derivedFrom',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'],
)]
#[FHIRProfileMustSupport(path: 'code', groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'])]
#[FHIRProfileMustSupport(path: 'valueQuantity', groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'])]
#[FHIRProfileMustSupport(path: 'interpretation', groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'])]
#[FHIRProfileMustSupport(path: 'note', groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'])]
#[FHIRProfileMustSupport(path: 'referenceRange', groups: ['http://hl7.org/fhir/StructureDefinition/triglyceride'])]
class TriglycerideProfile extends ObservationResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/triglyceride';
}

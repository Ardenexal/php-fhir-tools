<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\GuidanceResponseResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/cdshooksguidanceresponse
 *
 * @description Defines a GuidanceResponse that represents the response container for a CDS Hooks response
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/cdshooksguidanceresponse',
    baseType: 'GuidanceResponse',
    fhirVersion: 'R4B',
)]
#[FHIRProfileConstraint(
    path: 'extension',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksguidanceresponse'],
)]
#[FHIRProfileConstraint(
    path: 'requestIdentifier',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksguidanceresponse'],
)]
#[FHIRProfileConstraint(
    path: 'identifier',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksguidanceresponse'],
)]
#[FHIRProfileConstraint(
    path: 'moduleUri',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksguidanceresponse'],
)]
#[FHIRProfileMustSupport(path: 'subject', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksguidanceresponse'])]
#[FHIRProfileMustSupport(path: 'occurrenceDateTime', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksguidanceresponse'])]
#[FHIRProfileMustSupport(path: 'performer', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksguidanceresponse'])]
#[FHIRProfileMustSupport(path: 'result', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksguidanceresponse'])]
class CDShooksGuidanceResponseProfile extends GuidanceResponseResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/cdshooksguidanceresponse';
}

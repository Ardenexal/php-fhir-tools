<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ObservationResource;

/**
 * @author Health Level Seven International (Health Care Devices)
 *
 * @see http://hl7.org/fhir/StructureDefinition/devicemetricobservation
 *
 * @description This profile describes the direct or derived, qualitative or quantitative physiological measurement, setting, or calculation data produced by a medical device or a device component.
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/devicemetricobservation',
    baseType: 'Observation',
    fhirVersion: 'R4B',
)]
#[FHIRProfileConstraint(
    path: 'status',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'code',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'subject',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'encounter',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'effectiveDateTime',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'issued',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'value[x]',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'dataAbsentReason',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'interpretation',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'bodySite',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'method',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'specimen',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'device',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileConstraint(
    path: 'referenceRange',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'],
)]
#[FHIRProfileMustSupport(path: 'identifier', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
#[FHIRProfileMustSupport(path: 'status', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
#[FHIRProfileMustSupport(path: 'code', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
#[FHIRProfileMustSupport(path: 'subject', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
#[FHIRProfileMustSupport(path: 'effectiveDateTime', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
#[FHIRProfileMustSupport(path: 'value[x]', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
#[FHIRProfileMustSupport(path: 'bodySite', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
#[FHIRProfileMustSupport(path: 'method', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
#[FHIRProfileMustSupport(path: 'device', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
#[FHIRProfileMustSupport(path: 'referenceRange', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
#[FHIRProfileMustSupport(path: 'hasMember', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
#[FHIRProfileMustSupport(path: 'derivedFrom', groups: ['http://hl7.org/fhir/StructureDefinition/devicemetricobservation'])]
class DeviceMetricObservationProfile extends ObservationResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/devicemetricobservation';
}

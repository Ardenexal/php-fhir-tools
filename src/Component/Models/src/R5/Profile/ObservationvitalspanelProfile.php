<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;

/**
 * @author Health Level Seven International (Orders and Observations Workgroup)
 *
 * @see http://hl7.org/fhir/StructureDefinition/vitalspanel
 *
 * @description FHIR Vital Signs Panel Profile
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/vitalspanel', baseType: 'Observation', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'code.coding.system',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'http://loinc.org'],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalspanel'],
)]
#[FHIRProfileConstraint(
    path: 'code.coding.code',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => '85353-1'],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalspanel'],
)]
#[FHIRProfileConstraint(
    path: 'value[x]',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalspanel'],
)]
#[FHIRProfileConstraint(
    path: 'hasMember',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalspanel'],
)]
#[FHIRProfileMustSupport(path: 'hasMember', groups: ['http://hl7.org/fhir/StructureDefinition/vitalspanel'])]
#[FHIRSlicingRules(property: 'code.coding', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/vitalspanel'])]
#[FHIRSliceConstraint(
    property: 'code.coding',
    sliceName: 'VitalsPanelCode',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'code',
    groups: ['http://hl7.org/fhir/StructureDefinition/vitalspanel'],
    orderedIndex: 0,
    discriminatorValue: '85353-1',
)]
class ObservationvitalspanelProfile extends ObservationvitalsignsProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/vitalspanel';
}

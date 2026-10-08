<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\MeasureResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/shareablemeasure
 *
 * @description Enforces the minimum information set for the measure metadata required by HL7 and other organizations that share and publish measures
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/shareablemeasure', baseType: 'Measure', fhirVersion: 'R5')]
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
    path: 'title',
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
#[FHIRProfileMustSupport(path: 'url', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'version', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'versionAlgorithm[x]', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'name', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'title', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'experimental', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'publisher', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRProfileMustSupport(path: 'description', groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'])]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'knowledgeCapability',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/cqf-knowledgeCapability',
    orderedIndex: 0,
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'knowledgeRepresentationLevel',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/cqf-knowledgeRepresentationLevel',
    orderedIndex: 1,
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'artifactComment',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/cqf-artifactComment',
    orderedIndex: 2,
    groups: ['http://hl7.org/fhir/StructureDefinition/shareablemeasure'],
)]
class ShareableMeasureProfile extends MeasureResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/shareablemeasure';
}

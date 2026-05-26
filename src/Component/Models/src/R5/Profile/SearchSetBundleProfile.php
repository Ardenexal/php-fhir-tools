<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

/**
 * @see http://hl7.org/fhir/StructureDefinition/search-set-bundle
 *
 * @description This profile holds all the requirements and constraints related to a FHIR search bundle.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/search-set-bundle', baseType: 'Bundle', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
    options: ['pattern' => 'searchset'],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.fullUrl',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.resource',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.search.mode',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.search.mode',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
    options: ['pattern' => 'outcome'],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.request',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.response',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.fullUrl',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.resource',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.request',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.response',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRSlicingRules(property: 'entry', rules: 'closed', groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'])]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'operationOutcome',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'search.mode',
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
    orderedIndex: 0,
    discriminatorValue: 'outcome',
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'other',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'search.mode',
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
    orderedIndex: 1,
    discriminatorValue: 'outcome',
)]
class SearchSetBundleProfile extends BundleResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/search-set-bundle';
}

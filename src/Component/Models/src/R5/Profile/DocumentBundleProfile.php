<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

/**
 * @see http://hl7.org/fhir/StructureDefinition/document-bundle
 *
 * @description This profile holds all the requirements and constraints related to a FHIR document.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/document-bundle', baseType: 'Bundle', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'identifier',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'identifier.system',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'identifier.value',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => 'document'],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'timestamp',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'total',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.fullUrl',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.resource',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.search',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.request',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.response',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'issues',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/document-bundle'],
)]
class DocumentBundleProfile extends BundleResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/document-bundle';
}

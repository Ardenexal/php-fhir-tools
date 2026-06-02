<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

/**
 * @see http://hl7.org/fhir/StructureDefinition/transaction-bundle
 *
 * @description This profile holds all the requirements and constraints related to a FHIR transaction.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/transaction-bundle', baseType: 'Bundle', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'total',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.fullUrl',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.resource',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.search',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.request',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.response',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.resource',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.search',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.request',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.response',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.fullUrl',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.resource',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.search',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.request',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.response',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.fullUrl',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.resource',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.search',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.request',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.response',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.fullUrl',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.resource',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.search',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.request',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.response',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.fullUrl',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.resource',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.search',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.request',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRProfileConstraint(
    path: 'entry.response',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
)]
#[FHIRSlicingRules(property: 'entry', rules: 'closed', groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'])]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'put',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'request.method',
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
    orderedIndex: 0,
    discriminatorValue: 'PUT',
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'post',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'request.method',
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
    orderedIndex: 1,
    discriminatorValue: 'PUT',
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'get',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'request.method',
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
    orderedIndex: 2,
    discriminatorValue: 'PUT',
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'delete',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'request.method',
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
    orderedIndex: 3,
    discriminatorValue: 'PUT',
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'patch',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'request.method',
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
    orderedIndex: 4,
    discriminatorValue: 'PUT',
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'head',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'request.method',
    groups: ['http://hl7.org/fhir/StructureDefinition/transaction-bundle'],
    orderedIndex: 5,
    discriminatorValue: 'PUT',
)]
class TransactionBundleProfile extends BundleResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/transaction-bundle';
}

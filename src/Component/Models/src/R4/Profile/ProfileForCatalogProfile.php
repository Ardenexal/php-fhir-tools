<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\CompositionResource;

/**
 * @author Health Level Seven, Inc. - Clinical Quality Information WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/catalog
 *
 * @description A set of resources composed into a single coherent clinical statement with clinical attestation
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/catalog', baseType: 'Composition', fhirVersion: 'R4')]
#[FHIRProfileConstraint(
    path: 'extension',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/catalog'],
)]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/catalog'],
)]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRFixedValue',
    options: ['value' => ['text' => 'Catalog']],
    groups: ['http://hl7.org/fhir/StructureDefinition/catalog'],
)]
#[FHIRProfileConstraint(
    path: 'category',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/catalog'],
)]
#[FHIRProfileConstraint(
    path: 'subject',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 0],
    groups: ['http://hl7.org/fhir/StructureDefinition/catalog'],
)]
#[FHIRProfileConstraint(
    path: 'date',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/catalog'],
)]
#[FHIRProfileConstraint(
    path: 'section.entry',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/catalog'],
)]
class ProfileForCatalogProfile extends CompositionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/catalog';
}

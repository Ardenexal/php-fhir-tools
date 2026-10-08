<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

/**
 * @see http://hl7.org/fhir/StructureDefinition/history-bundle
 *
 * @description This profile holds all the requirements and constraints related to a FHIR history bundle.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/history-bundle', baseType: 'Bundle', fhirVersion: 'R5')]
#[FHIRSlicingRules(property: 'entry', rules: 'closed', groups: ['http://hl7.org/fhir/StructureDefinition/history-bundle'])]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'put',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'request.method',
    groups: ['http://hl7.org/fhir/StructureDefinition/history-bundle'],
    orderedIndex: 0,
    discriminatorValue: 'PUT',
    rules: [
        ['path' => 'fullUrl', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'resource', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'search', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
        ['path' => 'request', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'response', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
    ],
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'post',
    min: 0,
    max: 0,
    discriminatorType: 'value',
    discriminatorPath: 'request.method',
    groups: ['http://hl7.org/fhir/StructureDefinition/history-bundle'],
    orderedIndex: 1,
    discriminatorValue: 'POST',
    rules: [
        ['path' => 'resource', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'search', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
        ['path' => 'request', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'response', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
    ],
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'get',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'request.method',
    groups: ['http://hl7.org/fhir/StructureDefinition/history-bundle'],
    orderedIndex: 2,
    discriminatorValue: 'GET',
    rules: [
        ['path' => 'fullUrl', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'resource', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
        ['path' => 'search', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
        ['path' => 'request', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'response', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
    ],
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'delete',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'request.method',
    groups: ['http://hl7.org/fhir/StructureDefinition/history-bundle'],
    orderedIndex: 3,
    discriminatorValue: 'DELETE',
    rules: [
        ['path' => 'fullUrl', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'resource', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
        ['path' => 'search', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
        ['path' => 'request', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'response', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
    ],
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'patch',
    min: 0,
    max: 0,
    discriminatorType: 'value',
    discriminatorPath: 'request.method',
    groups: ['http://hl7.org/fhir/StructureDefinition/history-bundle'],
    orderedIndex: 4,
    rules: [
        ['path' => 'fullUrl', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
    ],
)]
class HistoryBundleProfile extends BundleResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/history-bundle';
}

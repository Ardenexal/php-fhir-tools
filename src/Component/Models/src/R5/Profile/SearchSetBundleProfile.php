<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R5\Resource\BundleResource;

/**
 * @see http://hl7.org/fhir/StructureDefinition/search-set-bundle
 *
 * @description This profile holds all the requirements and constraints related to a FHIR search bundle.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/search-set-bundle', baseType: 'Bundle', fhirVersion: 'R5')]
#[FHIRSlicingRules(property: 'entry', rules: 'closed', groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'])]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'operationOutcome',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'search.mode',
    discriminatorValue: 'outcome',
    orderedIndex: 0,
    rules: [
        ['path' => 'fullUrl', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'resource', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        [
            'path'       => 'search.mode',
            'constraint' => 'Symfony\Component\Validator\Constraints\Count',
            'options'    => ['min' => 1],
        ],
        ['path' => 'request', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
        ['path' => 'response', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
#[FHIRSliceConstraint(
    property: 'entry',
    sliceName: 'other',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'search.mode',
    orderedIndex: 1,
    rules: [
        ['path' => 'fullUrl', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'resource', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['min' => 1]],
        ['path' => 'request', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
        ['path' => 'response', 'constraint' => 'Symfony\Component\Validator\Constraints\Count', 'options' => ['max' => 0]],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/search-set-bundle'],
)]
class SearchSetBundleProfile extends BundleResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/search-set-bundle';
}

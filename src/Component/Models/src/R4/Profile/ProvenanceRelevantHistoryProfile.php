<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSlicingRules;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\ProvenanceResource;

/**
 * @author Health Level Seven International
 *
 * @see http://hl7.org/fhir/StructureDefinition/provenance-relevant-history
 *
 * @description Guidance on using Provenance for related history elements
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/provenance-relevant-history',
    baseType: 'Provenance',
    fhirVersion: 'R4',
)]
#[FHIRProfileConstraint(
    path: 'occurred[x]',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'],
)]
#[FHIRProfileConstraint(
    path: 'activity',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'],
)]
#[FHIRProfileConstraint(
    path: 'agent.type',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'],
)]
#[FHIRProfileConstraint(
    path: 'agent',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'],
)]
#[FHIRProfileConstraint(
    path: 'agent.type',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'],
)]
#[FHIRProfileConstraint(
    path: 'agent.type',
    constraint: 'Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPatternValue',
    options: [
        'pattern' => [
            'coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'code' => 'AUT']],
        ],
    ],
    groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'],
)]
#[FHIRProfileConstraint(
    path: 'agent.who',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'],
)]
#[FHIRProfileMustSupport(path: 'target', groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'])]
#[FHIRProfileMustSupport(path: 'occurred[x]', groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'])]
#[FHIRProfileMustSupport(path: 'reason', groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'])]
#[FHIRProfileMustSupport(path: 'activity', groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'])]
#[FHIRProfileMustSupport(path: 'agent', groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'])]
#[FHIRProfileMustSupport(path: 'agent.type', groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'])]
#[FHIRProfileMustSupport(path: 'agent', groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'])]
#[FHIRProfileMustSupport(path: 'agent.type', groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'])]
#[FHIRProfileMustSupport(path: 'agent.who', groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'])]
#[FHIRSlicingRules(property: 'agent', rules: 'open', groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'])]
#[FHIRSliceConstraint(
    property: 'agent',
    sliceName: 'Author',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'type',
    groups: ['http://hl7.org/fhir/StructureDefinition/provenance-relevant-history'],
    orderedIndex: 0,
    discriminatorValue: [
        'coding' => [['system' => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType', 'code' => 'AUT']],
    ],
)]
class ProvenanceRelevantHistoryProfile extends ProvenanceResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/provenance-relevant-history';
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\ObservationResource;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/observation-genetics
 *
 * @description Describes how the observation resource is used to report structured genetic test results
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/observation-genetics', baseType: 'Observation', fhirVersion: 'R4B')]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'Gene',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsGene',
    orderedIndex: 0,
    groups: ['http://hl7.org/fhir/StructureDefinition/observation-genetics'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'DNARegionName',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsDNARegionName',
    orderedIndex: 1,
    groups: ['http://hl7.org/fhir/StructureDefinition/observation-genetics'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'CopyNumberEvent',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsCopyNumberEvent',
    orderedIndex: 2,
    groups: ['http://hl7.org/fhir/StructureDefinition/observation-genetics'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'GenomicSourceClass',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsGenomicSourceClass',
    orderedIndex: 3,
    groups: ['http://hl7.org/fhir/StructureDefinition/observation-genetics'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'Interpretation',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsInterpretation',
    orderedIndex: 4,
    groups: ['http://hl7.org/fhir/StructureDefinition/observation-genetics'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'Variant',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsVariant',
    orderedIndex: 5,
    groups: ['http://hl7.org/fhir/StructureDefinition/observation-genetics'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'AminoAcidChange',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsAminoAcidChange',
    orderedIndex: 6,
    groups: ['http://hl7.org/fhir/StructureDefinition/observation-genetics'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'Allele',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsAllele',
    orderedIndex: 7,
    groups: ['http://hl7.org/fhir/StructureDefinition/observation-genetics'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'Ancestry',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsAncestry',
    orderedIndex: 8,
    groups: ['http://hl7.org/fhir/StructureDefinition/observation-genetics'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'PhaseSet',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/observation-geneticsPhaseSet',
    orderedIndex: 9,
    groups: ['http://hl7.org/fhir/StructureDefinition/observation-genetics'],
)]
class ObservationGeneticsProfile extends ObservationResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/observation-genetics';
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\DiagnosticReportResource;

/**
 * @author Health Level Seven International (Clinical Genomics)
 *
 * @see http://hl7.org/fhir/StructureDefinition/hlaresult
 *
 * @description Describes how the HLA genotyping results
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/hlaresult', baseType: 'DiagnosticReport', fhirVersion: 'R4')]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'allele-database',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-allele-database',
    orderedIndex: 0,
    groups: ['http://hl7.org/fhir/StructureDefinition/hlaresult'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'glstring',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-glstring',
    orderedIndex: 1,
    groups: ['http://hl7.org/fhir/StructureDefinition/hlaresult'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'haploid',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-haploid',
    orderedIndex: 2,
    groups: ['http://hl7.org/fhir/StructureDefinition/hlaresult'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'method',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/hla-genotyping-results-method',
    orderedIndex: 3,
    groups: ['http://hl7.org/fhir/StructureDefinition/hlaresult'],
)]
class ProfileForHLAGenotypingResultsProfile extends DiagnosticReportResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/hlaresult';
}

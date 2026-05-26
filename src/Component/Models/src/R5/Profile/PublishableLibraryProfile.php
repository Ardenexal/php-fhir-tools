<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/publishablelibrary
 *
 * @description Supports declaration of the library metadata required by HL7 and other organizations that share and publish libraries with a focus on the aspects of that metadata that are important for post-publication activities including distribution, inclusion in repositories, consumption, and implementation.
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/publishablelibrary', baseType: 'Library', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'type',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'],
)]
#[FHIRProfileConstraint(
    path: 'date',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'],
)]
#[FHIRProfileMustSupport(path: 'extension', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'identifier', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'type', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'date', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'contact', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'useContext', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'jurisdiction', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'purpose', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'usage', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'copyright', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'copyrightLabel', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'approvalDate', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'lastReviewDate', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'effectivePeriod', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'topic', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'author', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'editor', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'reviewer', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'endorser', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact.display', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact.document', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact.resource', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact.resourceReference', groups: ['http://hl7.org/fhir/StructureDefinition/publishablelibrary'])]
class PublishableLibraryProfile extends ShareableLibraryProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/publishablelibrary';
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition
 *
 * @description Supports declaration of the ActivityDefinition metadata required by HL7 and other organizations that share and publish activity definitions with a focus on the aspects of that metadata that are important for post-publication activities including distribution, inclusion in repositories, consumption, and implementation.
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition',
    baseType: 'ActivityDefinition',
    fhirVersion: 'R5',
)]
#[FHIRProfileConstraint(
    path: 'date',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'],
)]
#[FHIRProfileMustSupport(path: 'identifier', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'date', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'contact', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'useContext', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'jurisdiction', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'purpose', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'usage', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'copyright', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'copyrightLabel', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'approvalDate', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'lastReviewDate', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'effectivePeriod', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'topic', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'author', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'editor', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'reviewer', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'endorser', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact.display', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact.document', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(path: 'relatedArtifact.resource', groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'])]
#[FHIRProfileMustSupport(
    path: 'relatedArtifact.resourceReference',
    groups: ['http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition'],
)]
class PublishableActivityDefinitionProfile extends ShareableActivityDefinitionProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/publishableactivitydefinition';
}

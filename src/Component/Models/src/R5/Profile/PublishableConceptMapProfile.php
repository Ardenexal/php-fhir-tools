<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/publishableconceptmap
 *
 * @description Defines and enforces the minimum expectations for publication and distribution of a concept map, typically as part of an artifact repository or implementation guide publication
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/publishableconceptmap', baseType: 'ConceptMap', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'date',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/publishableconceptmap'],
)]
#[FHIRProfileMustSupport(path: 'identifier', groups: ['http://hl7.org/fhir/StructureDefinition/publishableconceptmap'])]
#[FHIRProfileMustSupport(path: 'date', groups: ['http://hl7.org/fhir/StructureDefinition/publishableconceptmap'])]
#[FHIRProfileMustSupport(path: 'contact', groups: ['http://hl7.org/fhir/StructureDefinition/publishableconceptmap'])]
#[FHIRProfileMustSupport(path: 'useContext', groups: ['http://hl7.org/fhir/StructureDefinition/publishableconceptmap'])]
#[FHIRProfileMustSupport(path: 'jurisdiction', groups: ['http://hl7.org/fhir/StructureDefinition/publishableconceptmap'])]
#[FHIRProfileMustSupport(path: 'purpose', groups: ['http://hl7.org/fhir/StructureDefinition/publishableconceptmap'])]
#[FHIRProfileMustSupport(path: 'copyright', groups: ['http://hl7.org/fhir/StructureDefinition/publishableconceptmap'])]
#[FHIRProfileMustSupport(path: 'copyrightLabel', groups: ['http://hl7.org/fhir/StructureDefinition/publishableconceptmap'])]
#[FHIRProfileMustSupport(path: 'effectivePeriod', groups: ['http://hl7.org/fhir/StructureDefinition/publishableconceptmap'])]
#[FHIRProfileMustSupport(path: 'topic', groups: ['http://hl7.org/fhir/StructureDefinition/publishableconceptmap'])]
class PublishableConceptMapProfile extends ShareableConceptMapProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/publishableconceptmap';
}

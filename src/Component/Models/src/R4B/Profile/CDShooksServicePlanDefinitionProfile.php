<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\PlanDefinitionResource;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition
 *
 * @description Defines a PlanDefinition that implements the behavior for a CDS Hooks service
 */
#[FHIRProfile(
    profileUrl: 'http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition',
    baseType: 'PlanDefinition',
    fhirVersion: 'R4B',
)]
#[FHIRProfileConstraint(
    path: 'extension',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition'],
)]
#[FHIRProfileMustSupport(path: 'action.title', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition'])]
#[FHIRProfileMustSupport(path: 'action.description', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition'])]
#[FHIRProfileMustSupport(path: 'action.priority', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition'])]
#[FHIRProfileMustSupport(path: 'action.trigger', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition'])]
#[FHIRProfileMustSupport(path: 'action.condition', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition'])]
#[FHIRProfileMustSupport(path: 'action.timing[x]', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition'])]
#[FHIRProfileMustSupport(path: 'action.participant', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition'])]
#[FHIRProfileMustSupport(path: 'action.type', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition'])]
#[FHIRProfileMustSupport(path: 'action.selectionBehavior', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition'])]
#[FHIRProfileMustSupport(path: 'action.definition[x]', groups: ['http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition'])]
class CDShooksServicePlanDefinitionProfile extends PlanDefinitionResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/cdshooksserviceplandefinition';
}

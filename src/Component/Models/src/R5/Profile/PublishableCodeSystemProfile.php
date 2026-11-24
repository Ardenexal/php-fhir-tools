<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileConstraint;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRProfileMustSupport;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;

/**
 * @author HL7
 *
 * @see http://hl7.org/fhir/StructureDefinition/publishablecodesystem
 *
 * @description Defines and enforces the minimum expectations for publication and distribution of a code system, typically as part of an artifact repository or implementation guide publication
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/publishablecodesystem', baseType: 'CodeSystem', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'date',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'],
)]
#[FHIRProfileMustSupport(path: 'identifier', groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'])]
#[FHIRProfileMustSupport(path: 'date', groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'])]
#[FHIRProfileMustSupport(path: 'contact', groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'])]
#[FHIRProfileMustSupport(path: 'useContext', groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'])]
#[FHIRProfileMustSupport(path: 'jurisdiction', groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'])]
#[FHIRProfileMustSupport(path: 'purpose', groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'])]
#[FHIRProfileMustSupport(path: 'copyright', groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'])]
#[FHIRProfileMustSupport(path: 'copyrightLabel', groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'])]
#[FHIRProfileMustSupport(path: 'effectivePeriod', groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'])]
#[FHIRProfileMustSupport(path: 'topic', groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'])]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'sourceReference',
    min: 0,
    max: 1,
    discriminators: [
        [
            'type'  => 'value',
            'path'  => 'url',
            'value' => 'http://hl7.org/fhir/StructureDefinition/codesystem-sourceReference',
        ],
    ],
    orderedIndex: 1,
    groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'otherName',
    min: 0,
    max: '*',
    discriminators: [
        ['type' => 'value', 'path' => 'url', 'value' => 'http://hl7.org/fhir/StructureDefinition/codesystem-otherName'],
    ],
    orderedIndex: 2,
    groups: ['http://hl7.org/fhir/StructureDefinition/publishablecodesystem'],
)]
class PublishableCodeSystemProfile extends ShareableCodeSystemProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/publishablecodesystem';
}

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
 * @see http://hl7.org/fhir/StructureDefinition/publishablevalueset
 *
 * @description Defines and enforces the minimum expectations for publication and distribution of a value set, typically as part of an artifact repository or implementation guide publication
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/publishablevalueset', baseType: 'ValueSet', fhirVersion: 'R5')]
#[FHIRProfileConstraint(
    path: 'date',
    constraint: 'Symfony\Component\Validator\Constraints\Count',
    options: ['min' => 1, 'max' => 1],
    groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'],
)]
#[FHIRProfileMustSupport(path: 'identifier', groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'])]
#[FHIRProfileMustSupport(path: 'date', groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'])]
#[FHIRProfileMustSupport(path: 'contact', groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'])]
#[FHIRProfileMustSupport(path: 'useContext', groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'])]
#[FHIRProfileMustSupport(path: 'jurisdiction', groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'])]
#[FHIRProfileMustSupport(path: 'purpose', groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'])]
#[FHIRProfileMustSupport(path: 'copyright', groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'])]
#[FHIRProfileMustSupport(path: 'copyrightLabel', groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'])]
#[FHIRProfileMustSupport(path: 'effectivePeriod', groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'])]
#[FHIRProfileMustSupport(path: 'topic', groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'])]
#[FHIRProfileMustSupport(path: 'compose.include.copyright', groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'])]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'trustedExpansion',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/valueset-trusted-expansion',
    orderedIndex: 0,
    groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'otherTitle',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/valueset-otherTitle',
    orderedIndex: 1,
    groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'],
)]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'sourceReference',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/valueset-sourceReference',
    orderedIndex: 2,
    groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'],
)]
#[FHIRSliceConstraint(
    property: 'compose.extension',
    sliceName: 'composeCreatedBy',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/valueset-compose-createdBy',
    orderedIndex: 0,
    groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'],
)]
#[FHIRSliceConstraint(
    property: 'compose.extension',
    sliceName: 'composeCreationDate',
    min: 0,
    max: 1,
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/valueset-compose-createdBy',
    orderedIndex: 1,
    groups: ['http://hl7.org/fhir/StructureDefinition/publishablevalueset'],
)]
class PublishableValueSetProfile extends ShareableValueSetProfile
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/publishablevalueset';
}

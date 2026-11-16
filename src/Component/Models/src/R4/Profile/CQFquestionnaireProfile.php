<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Profile;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRProfile;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRSliceConstraint;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\QuestionnaireResource;

/**
 * @author Health Level Seven, Inc. - CDS WG
 *
 * @see http://hl7.org/fhir/StructureDefinition/cqf-questionnaire
 *
 * @description A questionnaire with the ability to specify behavior associated with questions or groups of questions
 */
#[FHIRProfile(profileUrl: 'http://hl7.org/fhir/StructureDefinition/cqf-questionnaire', baseType: 'Questionnaire', fhirVersion: 'R4')]
#[FHIRSliceConstraint(
    property: 'extension',
    sliceName: 'library',
    min: 0,
    max: '*',
    discriminatorType: 'value',
    discriminatorPath: 'url',
    discriminatorValue: 'http://hl7.org/fhir/StructureDefinition/cqf-library',
    orderedIndex: 0,
    groups: ['http://hl7.org/fhir/StructureDefinition/cqf-questionnaire'],
)]
class CQFquestionnaireProfile extends QuestionnaireResource
{
    /** Canonical URL of this profile's StructureDefinition. */
    public const string PROFILE_URL = 'http://hl7.org/fhir/StructureDefinition/cqf-questionnaire';
}

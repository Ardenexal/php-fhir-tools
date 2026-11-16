<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Operation\PlanDefinitionApply;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationParameter;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirOperationPayload;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\Resource\PlanDefinitionResource;

#[FhirOperationPayload(
    operationUrl: 'http://hl7.org/fhir/OperationDefinition/PlanDefinition-apply',
    use: 'in',
    version: 'R4',
    operation: 'PlanDefinitionApply',
    path: '',
)]
final class PlanDefinitionApplyInput
{
    /**
     * @param list<string> $subject
     */
    public function __construct(
        #[FhirOperationParameter(
            name: 'planDefinition',
            phpName: 'planDefinition',
            use: 'in',
            min: 0,
            max: '1',
            type: 'PlanDefinition',
            documentation: 'The plan definition to be applied. If the operation is invoked at the instance level, this parameter is not allowed; if the operation is invoked at the type level, this parameter is required',
        )]
        public readonly ?PlanDefinitionResource $planDefinition = null,
        #[FhirOperationParameter(
            name: 'subject',
            phpName: 'subject',
            use: 'in',
            min: 1,
            max: '*',
            type: 'string',
            searchType: 'reference',
            documentation: 'The subject(s) that is/are the target of the plan to be applied. The subject may be a Patient, Practitioner, Organization, Location, Device, or Group. Subjects provided in this parameter will be resolved as the subject of the PlanDefinition based on the type of the subject. If multiple subjects of the same type are provided, the behavior is implementation-defined',
        )]
        public readonly array $subject = [],
        #[FhirOperationParameter(
            name: 'encounter',
            phpName: 'encounter',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            searchType: 'reference',
            documentation: 'The encounter in context, if any',
        )]
        public readonly ?string $encounter = null,
        #[FhirOperationParameter(
            name: 'practitioner',
            phpName: 'practitioner',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            searchType: 'reference',
            documentation: 'The practitioner applying the plan definition',
        )]
        public readonly ?string $practitioner = null,
        #[FhirOperationParameter(
            name: 'organization',
            phpName: 'organization',
            use: 'in',
            min: 0,
            max: '1',
            type: 'string',
            searchType: 'reference',
            documentation: 'The organization applying the plan definition',
        )]
        public readonly ?string $organization = null,
        #[FhirOperationParameter(
            name: 'userType',
            phpName: 'userType',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'The type of user initiating the request, e.g. patient, healthcare provider, or specific type of healthcare provider (physician, nurse, etc.)',
        )]
        public readonly ?CodeableConcept $userType = null,
        #[FhirOperationParameter(
            name: 'userLanguage',
            phpName: 'userLanguage',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'Preferred language of the person using the system',
        )]
        public readonly ?CodeableConcept $userLanguage = null,
        #[FhirOperationParameter(
            name: 'userTaskContext',
            phpName: 'userTaskContext',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'The task the system user is performing, e.g. laboratory results review, medication list review, etc. This information can be used to tailor decision support outputs, such as recommended information resources',
        )]
        public readonly ?CodeableConcept $userTaskContext = null,
        #[FhirOperationParameter(
            name: 'setting',
            phpName: 'setting',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'The current setting of the request (inpatient, outpatient, etc.)',
        )]
        public readonly ?CodeableConcept $setting = null,
        #[FhirOperationParameter(
            name: 'settingContext',
            phpName: 'settingContext',
            use: 'in',
            min: 0,
            max: '1',
            type: 'CodeableConcept',
            documentation: 'Additional detail about the setting of the request, if any',
        )]
        public readonly ?CodeableConcept $settingContext = null,
    ) {
    }
}

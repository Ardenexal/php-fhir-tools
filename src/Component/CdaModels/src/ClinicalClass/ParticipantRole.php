<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\AD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IdentifiedBy;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\TEL;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\RoleClassRoot;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/ParticipantRole',
    name: 'ParticipantRole',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'role-choice',
    severity: 'error',
    expression: '(playingDevice | playingEntity).count() <= 1',
    human: 'playingDevice and playingEntity are mutually exclusive',
)]
class ParticipantRole extends InfrastructureRoot
{
    /**
     * @param list<II>           $id
     * @param list<IdentifiedBy> $sdtcIdentifiedBy
     * @param list<CE>           $sdtcSpecialty
     * @param list<AD>           $addr
     * @param list<TEL>          $telecom
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'enum', isArray: false, isRequired: false, xmlSerializedName: '@classCode')]
        public ?RoleClassRoot $classCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\II',
        )]
        public array $id = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IdentifiedBy',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\IdentifiedBy',
        )]
        public array $sdtcIdentifiedBy = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CE',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?CE $code = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CE',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\CE',
        )]
        public array $sdtcSpecialty = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/AD',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\AD',
        )]
        public array $addr = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/TEL',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\TEL',
        )]
        public array $telecom = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Device',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Device $playingDevice = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PlayingEntity',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PlayingEntity $playingEntity = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Entity',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Entity $scopingEntity = null,
    ) {
    }
}

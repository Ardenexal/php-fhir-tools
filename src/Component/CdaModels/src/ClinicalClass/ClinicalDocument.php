<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\ANY;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\INTType;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ST;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\TS;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
    name: 'ClinicalDocument',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: [
        'nullFlavor',
        'classCode',
        'moodCode',
        'realmCode',
        'typeId',
        'templateId',
        'id',
        'sdtcCategory',
        'code',
        'title',
        'sdtcStatusCode',
        'effectiveTime',
        'confidentialityCode',
        'languageCode',
        'setId',
        'versionNumber',
        'copyTime',
        'recordTarget',
        'author',
        'dataEnterer',
        'informant',
        'custodian',
        'informationRecipient',
        'legalAuthenticator',
        'authenticator',
        'participant',
        'inFulfillmentOf',
        'documentationOf',
        'relatedDocument',
        'authorization',
        'componentOf',
        'component',
    ],
)]
class ClinicalDocument extends ANY
{
    /**
     * @param list<CS>                   $realmCode
     * @param list<II>                   $templateId
     * @param list<CD>                   $sdtcCategory
     * @param list<RecordTarget>         $recordTarget
     * @param list<Author>               $author
     * @param list<Informant>            $informant
     * @param list<InformationRecipient> $informationRecipient
     * @param list<Authenticator>        $authenticator
     * @param list<Participant1>         $participant
     * @param list<InFulfillmentOf>      $inFulfillmentOf
     * @param list<DocumentationOf>      $documentationOf
     * @param list<RelatedDocument>      $relatedDocument
     * @param list<Authorization>        $authorization
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@classCode')]
        public string $classCode = 'DOCCLIN',
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@moodCode')]
        public string $moodCode = 'EVN',
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CS',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\CS',
        )]
        public array $realmCode = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?II $typeId = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\II',
        )]
        public array $templateId = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
        )]
        public ?II $id = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CD',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\CD',
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public array $sdtcCategory = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CE',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
        )]
        public ?CE $code = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ST',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?ST $title = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public ?CS $sdtcStatusCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/TS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
        )]
        public ?TS $effectiveTime = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CE',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
        )]
        public ?CE $confidentialityCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?CS $languageCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?II $setId = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/INT',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?INTType $versionNumber = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/TS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?TS $copyTime = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/RecordTarget',
            propertyKind: 'complex',
            isArray: true,
            isRequired: true,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\RecordTarget',
        )]
        public array $recordTarget = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Author',
            propertyKind: 'complex',
            isArray: true,
            isRequired: true,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Author',
        )]
        public array $author = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/DataEnterer',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?DataEnterer $dataEnterer = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Informant',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Informant',
        )]
        public array $informant = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Custodian',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
        )]
        public ?Custodian $custodian = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/InformationRecipient',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\InformationRecipient',
        )]
        public array $informationRecipient = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/LegalAuthenticator',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?LegalAuthenticator $legalAuthenticator = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Authenticator',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Authenticator',
        )]
        public array $authenticator = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Participant1',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Participant1',
        )]
        public array $participant = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/InFulfillmentOf',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\InFulfillmentOf',
        )]
        public array $inFulfillmentOf = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/DocumentationOf',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\DocumentationOf',
        )]
        public array $documentationOf = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/RelatedDocument',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\RelatedDocument',
        )]
        public array $relatedDocument = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Authorization',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Authorization',
        )]
        public array $authorization = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ComponentOf',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?ComponentOf $componentOf = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Component',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
        )]
        public ?Component $component = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            nullFlavor: $nullFlavor,
        );
    }
}

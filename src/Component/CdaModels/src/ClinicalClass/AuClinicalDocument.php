<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

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
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-ClinicalDocument',
    name: 'au-ClinicalDocument',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/ClinicalDocument',
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
        'completionCode',
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
class AuClinicalDocument extends ClinicalDocument
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
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CE',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?CE $completionCode = null,
        string $classCode = 'DOCCLIN',
        string $moodCode = 'EVN',
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?II $id = null,
        array $sdtcCategory = [],
        ?CE $code = null,
        ?ST $title = null,
        ?CS $sdtcStatusCode = null,
        ?TS $effectiveTime = null,
        ?CE $confidentialityCode = null,
        ?CS $languageCode = null,
        ?II $setId = null,
        ?INTType $versionNumber = null,
        ?TS $copyTime = null,
        array $recordTarget = [],
        array $author = [],
        ?DataEnterer $dataEnterer = null,
        array $informant = [],
        ?Custodian $custodian = null,
        array $informationRecipient = [],
        ?LegalAuthenticator $legalAuthenticator = null,
        array $authenticator = [],
        array $participant = [],
        array $inFulfillmentOf = [],
        array $documentationOf = [],
        array $relatedDocument = [],
        array $authorization = [],
        ?ComponentOf $componentOf = null,
        ?Component $component = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            moodCode: $moodCode,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            id: $id,
            sdtcCategory: $sdtcCategory,
            code: $code,
            title: $title,
            sdtcStatusCode: $sdtcStatusCode,
            effectiveTime: $effectiveTime,
            confidentialityCode: $confidentialityCode,
            languageCode: $languageCode,
            setId: $setId,
            versionNumber: $versionNumber,
            copyTime: $copyTime,
            recordTarget: $recordTarget,
            author: $author,
            dataEnterer: $dataEnterer,
            informant: $informant,
            custodian: $custodian,
            informationRecipient: $informationRecipient,
            legalAuthenticator: $legalAuthenticator,
            authenticator: $authenticator,
            participant: $participant,
            inFulfillmentOf: $inFulfillmentOf,
            documentationOf: $documentationOf,
            relatedDocument: $relatedDocument,
            authorization: $authorization,
            componentOf: $componentOf,
            component: $component,
            nullFlavor: $nullFlavor,
        );
    }
}

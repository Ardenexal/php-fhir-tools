<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLINT;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ST;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\ActClassObservation;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Observation',
    name: 'au-Observation',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/Observation',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'classCode',
        'moodCode',
        'negationInd',
        'id',
        'sdtcCategory',
        'code',
        'derivationExpr',
        'text',
        'statusCode',
        'effectiveTime',
        'priorityCode',
        'repeatNumber',
        'languageCode',
        'value',
        'interpretationCode',
        'methodCode',
        'targetSiteCode',
        'subject',
        'specimen',
        'performer',
        'author',
        'informant',
        'participant',
        'entryRelationship',
        'reference',
        'precondition',
        'sdtcPrecondition2',
        'referenceRange',
        'sdtcInFulfillmentOf1',
        'inFulfillmentOf',
    ],
)]
class AuObservation extends Observation
{
    /**
     * @param list<AuInFulfillmentOf>         $inFulfillmentOf
     * @param list<II>                        $id
     * @param list<CD>                        $sdtcCategory
     * @param list<CD>                        $value
     * @param list<CE>                        $interpretationCode
     * @param list<CE>                        $methodCode
     * @param list<CD>                        $targetSiteCode
     * @param list<Specimen>                  $specimen
     * @param list<Performer2>                $performer
     * @param list<Author>                    $author
     * @param list<Informant>                 $informant
     * @param list<Participant2>              $participant
     * @param list<EntryRelationship>         $entryRelationship
     * @param list<Reference>                 $reference
     * @param list<Precondition>              $precondition
     * @param list<Precondition2>             $sdtcPrecondition2
     * @param list<ObservationReferenceRange> $referenceRange
     * @param list<InFulfillmentOf1>          $sdtcInFulfillmentOf1
     * @param list<CS>                        $realmCode
     * @param list<II>                        $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-InFulfillmentOf',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuInFulfillmentOf',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $inFulfillmentOf = [],
        ?ActClassObservation $classCode = null,
        ?string $moodCode = null,
        ?bool $negationInd = null,
        array $id = [],
        array $sdtcCategory = [],
        ?CD $code = null,
        ?ST $derivationExpr = null,
        ?ED $text = null,
        ?CS $statusCode = null,
        ?IVLTS $effectiveTime = null,
        ?CE $priorityCode = null,
        ?IVLINT $repeatNumber = null,
        ?CS $languageCode = null,
        array $value = [],
        array $interpretationCode = [],
        array $methodCode = [],
        array $targetSiteCode = [],
        ?Subject $subject = null,
        array $specimen = [],
        array $performer = [],
        array $author = [],
        array $informant = [],
        array $participant = [],
        array $entryRelationship = [],
        array $reference = [],
        array $precondition = [],
        array $sdtcPrecondition2 = [],
        array $referenceRange = [],
        array $sdtcInFulfillmentOf1 = [],
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            moodCode: $moodCode,
            negationInd: $negationInd,
            id: $id,
            sdtcCategory: $sdtcCategory,
            code: $code,
            derivationExpr: $derivationExpr,
            text: $text,
            statusCode: $statusCode,
            effectiveTime: $effectiveTime,
            priorityCode: $priorityCode,
            repeatNumber: $repeatNumber,
            languageCode: $languageCode,
            value: $value,
            interpretationCode: $interpretationCode,
            methodCode: $methodCode,
            targetSiteCode: $targetSiteCode,
            subject: $subject,
            specimen: $specimen,
            performer: $performer,
            author: $author,
            informant: $informant,
            participant: $participant,
            entryRelationship: $entryRelationship,
            reference: $reference,
            precondition: $precondition,
            sdtcPrecondition2: $sdtcPrecondition2,
            referenceRange: $referenceRange,
            sdtcInFulfillmentOf1: $sdtcInFulfillmentOf1,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}

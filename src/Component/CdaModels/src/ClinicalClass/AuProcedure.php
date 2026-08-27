<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Procedure',
    name: 'au-Procedure',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuProcedure extends Procedure
{
    /**
     * @param list<InFulfillmentOf1>  $inFulfillmentOf1
     * @param list<II>                $id
     * @param list<CD>                $sdtcCategory
     * @param list<CE>                $methodCode
     * @param list<CD>                $approachSiteCode
     * @param list<CD>                $targetSiteCode
     * @param list<Specimen>          $specimen
     * @param list<Performer2>        $performer
     * @param list<Author>            $author
     * @param list<Informant>         $informant
     * @param list<Participant2>      $participant
     * @param list<EntryRelationship> $entryRelationship
     * @param list<Reference>         $reference
     * @param list<Precondition>      $precondition
     * @param list<Precondition2>     $sdtcPrecondition2
     * @param list<InFulfillmentOf1>  $sdtcInFulfillmentOf1
     * @param list<CS>                $realmCode
     * @param list<II>                $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/InFulfillmentOf1',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\InFulfillmentOf1',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $inFulfillmentOf1 = [],
        string $classCode = 'PROC',
        ?string $moodCode = null,
        array $id = [],
        array $sdtcCategory = [],
        ?CD $code = null,
        ?bool $negationInd = null,
        ?ED $text = null,
        ?CS $statusCode = null,
        ?IVLTS $effectiveTime = null,
        ?CE $priorityCode = null,
        ?CS $languageCode = null,
        array $methodCode = [],
        array $approachSiteCode = [],
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
        array $sdtcInFulfillmentOf1 = [],
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            moodCode: $moodCode,
            id: $id,
            sdtcCategory: $sdtcCategory,
            code: $code,
            negationInd: $negationInd,
            text: $text,
            statusCode: $statusCode,
            effectiveTime: $effectiveTime,
            priorityCode: $priorityCode,
            languageCode: $languageCode,
            methodCode: $methodCode,
            approachSiteCode: $approachSiteCode,
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
            sdtcInFulfillmentOf1: $sdtcInFulfillmentOf1,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}

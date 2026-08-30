<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\BL;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLINT;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\PQ;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\SXCMTS;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Supply',
    name: 'au-Supply',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/Supply',
)]
class AuSupply extends Supply
{
    /**
     * @param list<AuCoverage2>       $coverage2
     * @param list<AuInFulfillmentOf> $inFulfillmentOf
     * @param list<II>                $id
     * @param list<SXCMTS>            $effectiveTime
     * @param list<CE>                $priorityCode
     * @param list<Specimen>          $specimen
     * @param list<Performer2>        $performer
     * @param list<Author>            $author
     * @param list<Informant>         $informant
     * @param list<Participant2>      $participant
     * @param list<EntryRelationship> $entryRelationship
     * @param list<Reference>         $reference
     * @param list<Precondition>      $precondition
     * @param list<InFulfillmentOf1>  $sdtcInFulfillmentOf1
     * @param list<CS>                $realmCode
     * @param list<II>                $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/subjectOf2',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?AuSubjectOf2 $subjectOf2 = null,
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/coverage2',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuCoverage2',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $coverage2 = [],
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-InFulfillmentOf',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuInFulfillmentOf',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $inFulfillmentOf = [],
        string $classCode = 'SPLY',
        ?string $moodCode = null,
        array $id = [],
        ?CD $code = null,
        ?ED $text = null,
        ?CS $statusCode = null,
        array $effectiveTime = [],
        array $priorityCode = [],
        ?IVLINT $repeatNumber = null,
        ?BL $independentInd = null,
        ?PQ $quantity = null,
        ?IVLTS $expectedUseTime = null,
        ?SupplyProduct $product = null,
        ?Subject $subject = null,
        array $specimen = [],
        array $performer = [],
        array $author = [],
        array $informant = [],
        array $participant = [],
        array $entryRelationship = [],
        array $reference = [],
        array $precondition = [],
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
            code: $code,
            text: $text,
            statusCode: $statusCode,
            effectiveTime: $effectiveTime,
            priorityCode: $priorityCode,
            repeatNumber: $repeatNumber,
            independentInd: $independentInd,
            quantity: $quantity,
            expectedUseTime: $expectedUseTime,
            product: $product,
            subject: $subject,
            specimen: $specimen,
            performer: $performer,
            author: $author,
            informant: $informant,
            participant: $participant,
            entryRelationship: $entryRelationship,
            reference: $reference,
            precondition: $precondition,
            sdtcInFulfillmentOf1: $sdtcInFulfillmentOf1,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLINT;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLPQ;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\RTOPQPQ;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\SXCMTS;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-SubstanceAdministration',
    name: 'au-SubstanceAdministration',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/SubstanceAdministration',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'classCode',
        'moodCode',
        'id',
        'code',
        'negationInd',
        'text',
        'statusCode',
        'effectiveTime',
        'priorityCode',
        'repeatNumber',
        'methodCode',
        'routeCode',
        'approachSiteCode',
        'doseQuantity',
        'rateQuantity',
        'maxDoseQuantity',
        'administrationUnitCode',
        'consumable',
        'subject',
        'specimen',
        'performer',
        'author',
        'informant',
        'participant',
        'entryRelationship',
        'reference',
        'precondition',
        'sdtcInFulfillmentOf1',
        'inFulfillmentOf1',
    ],
)]
class AuSubstanceAdministration extends SubstanceAdministration
{
    /**
     * @param list<InFulfillmentOf1>  $inFulfillmentOf1
     * @param list<II>                $id
     * @param list<SXCMTS>            $effectiveTime
     * @param list<CD>                $approachSiteCode
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
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CD',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?CD $methodCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/InFulfillmentOf1',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\InFulfillmentOf1',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $inFulfillmentOf1 = [],
        string $classCode = 'SBADM',
        ?string $moodCode = null,
        array $id = [],
        ?CD $code = null,
        ?bool $negationInd = null,
        ?ED $text = null,
        ?CS $statusCode = null,
        array $effectiveTime = [],
        ?CE $priorityCode = null,
        ?IVLINT $repeatNumber = null,
        ?CE $routeCode = null,
        array $approachSiteCode = [],
        ?IVLPQ $doseQuantity = null,
        ?IVLPQ $rateQuantity = null,
        ?RTOPQPQ $maxDoseQuantity = null,
        ?CE $administrationUnitCode = null,
        ?SubstanceAdministrationConsumable $consumable = null,
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
            negationInd: $negationInd,
            text: $text,
            statusCode: $statusCode,
            effectiveTime: $effectiveTime,
            priorityCode: $priorityCode,
            repeatNumber: $repeatNumber,
            routeCode: $routeCode,
            approachSiteCode: $approachSiteCode,
            doseQuantity: $doseQuantity,
            rateQuantity: $rateQuantity,
            maxDoseQuantity: $maxDoseQuantity,
            administrationUnitCode: $administrationUnitCode,
            consumable: $consumable,
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

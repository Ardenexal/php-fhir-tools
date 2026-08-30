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
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Encounter',
    name: 'au-Encounter',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/Encounter',
)]
class AuEncounter extends Encounter
{
    /**
     * @param list<AuInFulfillmentOf> $inFulfillmentOf
     * @param list<II>                $id
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
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CE',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?CE $admissionReferralSourceCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CE',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public ?CE $dischargeDispositionCode = null,
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-InFulfillmentOf',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuInFulfillmentOf',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $inFulfillmentOf = [],
        string $classCode = 'ENC',
        ?string $moodCode = null,
        array $id = [],
        ?CD $code = null,
        ?ED $text = null,
        ?CS $statusCode = null,
        ?IVLTS $effectiveTime = null,
        ?CE $sdtcDischargeDispositionCode = null,
        ?CE $priorityCode = null,
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
            code: $code,
            text: $text,
            statusCode: $statusCode,
            effectiveTime: $effectiveTime,
            sdtcDischargeDispositionCode: $sdtcDischargeDispositionCode,
            priorityCode: $priorityCode,
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

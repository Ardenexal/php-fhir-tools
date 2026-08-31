<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\INTType;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-RegionOfInterest',
    name: 'au-RegionOfInterest',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/RegionOfInterest',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'ID',
        'classCode',
        'moodCode',
        'id',
        'code',
        'value',
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
    ],
)]
class AuRegionOfInterest extends RegionOfInterest
{
    /**
     * @param list<II>                $id
     * @param list<INTType>           $value
     * @param list<Specimen>          $specimen
     * @param list<Performer2>        $performer
     * @param list<Author>            $author
     * @param list<Informant>         $informant
     * @param list<Participant2>      $participant
     * @param list<EntryRelationship> $entryRelationship
     * @param list<Reference>         $reference
     * @param list<Precondition>      $precondition
     * @param list<Precondition2>     $sdtcPrecondition2
     * @param list<CS>                $realmCode
     * @param list<II>                $templateId
     */
    public function __construct(
        ?string $ID = null,
        string $classCode = 'ROIOVL',
        string $moodCode = 'EVN',
        array $id = [],
        ?CS $code = null,
        array $value = [],
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
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            ID: $ID,
            classCode: $classCode,
            moodCode: $moodCode,
            id: $id,
            code: $code,
            value: $value,
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
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}

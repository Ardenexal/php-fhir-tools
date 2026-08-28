<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Organizer',
    name: 'au-Organizer',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/Organizer',
)]
class AuOrganizer extends Organizer
{
    /**
     * @param list<II>                 $id
     * @param list<CD>                 $sdtcCategory
     * @param list<Specimen>           $specimen
     * @param list<Performer2>         $performer
     * @param list<Author>             $author
     * @param list<Informant>          $informant
     * @param list<Participant2>       $participant
     * @param list<Reference>          $reference
     * @param list<Precondition>       $precondition
     * @param list<Precondition2>      $sdtcPrecondition2
     * @param list<OrganizerComponent> $component
     * @param list<CS>                 $realmCode
     * @param list<II>                 $templateId
     */
    public function __construct(
        ?string $classCode = null,
        string $moodCode = 'EVN',
        array $id = [],
        array $sdtcCategory = [],
        ?CD $code = null,
        ?ED $sdtcText = null,
        ?CS $statusCode = null,
        ?IVLTS $effectiveTime = null,
        ?Subject $subject = null,
        array $specimen = [],
        array $performer = [],
        array $author = [],
        array $informant = [],
        array $participant = [],
        array $reference = [],
        array $precondition = [],
        array $sdtcPrecondition2 = [],
        array $component = [],
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
            sdtcText: $sdtcText,
            statusCode: $statusCode,
            effectiveTime: $effectiveTime,
            subject: $subject,
            specimen: $specimen,
            performer: $performer,
            author: $author,
            informant: $informant,
            participant: $participant,
            reference: $reference,
            precondition: $precondition,
            sdtcPrecondition2: $sdtcPrecondition2,
            component: $component,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}

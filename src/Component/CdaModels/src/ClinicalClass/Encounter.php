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
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/Encounter',
    name: 'Encounter',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'classCode',
        'moodCode',
        'id',
        'code',
        'text',
        'statusCode',
        'effectiveTime',
        'sdtcDischargeDispositionCode',
        'priorityCode',
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
        'sdtcInFulfillmentOf1',
    ],
)]
class Encounter extends InfrastructureRoot
{
    /**
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
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: true, xmlSerializedName: '@classCode')]
        public string $classCode = 'ENC',
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: true, xmlSerializedName: '@moodCode')]
        public ?string $moodCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\II',
        )]
        public array $id = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CD',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?CD $code = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ED',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?ED $text = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?CS $statusCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-TS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?IVLTS $effectiveTime = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CE',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public ?CE $sdtcDischargeDispositionCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CE',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?CE $priorityCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Subject',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Subject $subject = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Specimen',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Specimen',
        )]
        public array $specimen = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Performer2',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Performer2',
        )]
        public array $performer = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Author',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Author',
        )]
        public array $author = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Informant',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Informant',
        )]
        public array $informant = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Participant2',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Participant2',
        )]
        public array $participant = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/EntryRelationship',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\EntryRelationship',
        )]
        public array $entryRelationship = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Reference',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Reference',
        )]
        public array $reference = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Precondition',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Precondition',
        )]
        public array $precondition = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Precondition2',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\Precondition2',
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public array $sdtcPrecondition2 = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/InFulfillmentOf1',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\InFulfillmentOf1',
            xmlNamespace: 'urn:hl7-org:sdtc',
        )]
        public array $sdtcInFulfillmentOf1 = [],
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}

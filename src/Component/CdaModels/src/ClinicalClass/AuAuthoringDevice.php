<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\SC;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-AuthoringDevice',
    name: 'au-AuthoringDevice',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AuAuthoringDevice extends AuthoringDevice
{
    /**
     * @param list<AuAsEntityIdentifier> $asEntityIdentifier
     * @param list<MaintainedEntity>     $asMaintainedEntity
     * @param list<CS>                   $realmCode
     * @param list<II>                   $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/asEntityIdentifier',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuAsEntityIdentifier',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $asEntityIdentifier = [],
        string $classCode = 'DEV',
        string $determinerCode = 'INSTANCE',
        ?CE $code = null,
        ?SC $manufacturerModelName = null,
        ?SC $softwareName = null,
        array $asMaintainedEntity = [],
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            determinerCode: $determinerCode,
            code: $code,
            manufacturerModelName: $manufacturerModelName,
            softwareName: $softwareName,
            asMaintainedEntity: $asMaintainedEntity,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}

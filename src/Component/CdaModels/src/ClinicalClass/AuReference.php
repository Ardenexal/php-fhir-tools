<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\BL;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-Reference',
    name: 'au-Reference',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/Reference',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'typeCode',
        'seperatableInd',
        'externalAct',
        'externalObservation',
        'externalProcedure',
        'externalDocument',
    ],
)]
class AuReference extends Reference
{
    /**
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        ?string $typeCode = null,
        ?BL $seperatableInd = null,
        ?ExternalAct $externalAct = null,
        ?ExternalObservation $externalObservation = null,
        ?ExternalProcedure $externalProcedure = null,
        ?ExternalDocument $externalDocument = null,
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            typeCode: $typeCode,
            seperatableInd: $seperatableInd,
            externalAct: $externalAct,
            externalObservation: $externalObservation,
            externalProcedure: $externalProcedure,
            externalDocument: $externalDocument,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}

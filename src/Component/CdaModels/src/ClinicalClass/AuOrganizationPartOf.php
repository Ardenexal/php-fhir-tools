<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CE;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IdentifiedBy;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-OrganizationPartOf',
    name: 'au-OrganizationPartOf',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/OrganizationPartOf',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'classCode',
        'id',
        'sdtcIdentifiedBy',
        'code',
        'statusCode',
        'effectiveTime',
        'wholeOrganization',
    ],
)]
class AuOrganizationPartOf extends OrganizationPartOf
{
    /**
     * @param list<II>           $id
     * @param list<IdentifiedBy> $sdtcIdentifiedBy
     * @param list<CS>           $realmCode
     * @param list<II>           $templateId
     */
    public function __construct(
        string $classCode = 'PART',
        array $id = [],
        array $sdtcIdentifiedBy = [],
        ?CE $code = null,
        ?CS $statusCode = null,
        ?IVLTS $effectiveTime = null,
        ?Organization $wholeOrganization = null,
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            id: $id,
            sdtcIdentifiedBy: $sdtcIdentifiedBy,
            code: $code,
            statusCode: $statusCode,
            effectiveTime: $effectiveTime,
            wholeOrganization: $wholeOrganization,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}

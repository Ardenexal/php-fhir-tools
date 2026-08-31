<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IdentifiedBy;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://ns.electronichealth.net.au/cda/StructureDefinition/au-ManufacturedProduct',
    name: 'au-ManufacturedProduct',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    refines: 'http://hl7.org/cda/stds/core/StructureDefinition/ManufacturedProduct',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'classCode',
        'id',
        'sdtcIdentifiedBy',
        'manufacturedLabeledDrug',
        'manufacturedMaterial',
        'manufacturerOrganization',
        'subjectOf1',
    ],
)]
class AuManufacturedProduct extends ManufacturedProduct
{
    /**
     * @param list<AuSubjectOf1> $subjectOf1
     * @param list<II>           $id
     * @param list<IdentifiedBy> $sdtcIdentifiedBy
     * @param list<CS>           $realmCode
     * @param list<II>           $templateId
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'http://ns.electronichealth.net.au/cda/StructureDefinition/subjectOf1',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass\AuSubjectOf1',
            xmlNamespace: 'http://ns.electronichealth.net.au/Ci/Cda/Extensions/3.0',
        )]
        public array $subjectOf1 = [],
        string $classCode = 'MANU',
        array $id = [],
        array $sdtcIdentifiedBy = [],
        ?LabeledDrug $manufacturedLabeledDrug = null,
        ?Material $manufacturedMaterial = null,
        ?Organization $manufacturerOrganization = null,
        array $realmCode = [],
        ?II $typeId = null,
        array $templateId = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            classCode: $classCode,
            id: $id,
            sdtcIdentifiedBy: $sdtcIdentifiedBy,
            manufacturedLabeledDrug: $manufacturedLabeledDrug,
            manufacturedMaterial: $manufacturedMaterial,
            manufacturerOrganization: $manufacturerOrganization,
            realmCode: $realmCode,
            typeId: $typeId,
            templateId: $templateId,
            nullFlavor: $nullFlavor,
        );
    }
}

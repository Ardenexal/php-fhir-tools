<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/InFulfillmentOf1',
    name: 'InFulfillmentOf1',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:sdtc',
)]
class InFulfillmentOf1 extends InfrastructureRoot
{
    /**
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: true, xmlSerializedName: '@typeCode')]
        public string $typeCode = 'FLFS',
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@inversionInd')]
        public ?bool $inversionInd = null,
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@negationInd')]
        public ?bool $negationInd = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/InfrastructureRoot',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
        )]
        public ?InfrastructureRoot $actReference = null,
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

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\EntityNameUse;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/EN',
    name: 'EN',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class EN extends ANY
{
    /**
     * @param list<EntityNameUse>   $use
     * @param list<ChoiceGroupItem> $item
     */
    public function __construct(
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'enum',
            isArray: true,
            isRequired: false,
            xmlSerializedName: '@use',
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\Enum\EntityNameUse',
        )]
        public array $use = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/fhir/StructureDefinition/Base',
            propertyKind: 'choiceGroup',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem',
            variants: [
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ENXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ENXP',
                    'jsonKey'      => 'delimiter',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ENXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ENXP',
                    'jsonKey'      => 'family',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ENXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ENXP',
                    'jsonKey'      => 'given',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ENXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ENXP',
                    'jsonKey'      => 'prefix',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ENXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ENXP',
                    'jsonKey'      => 'suffix',
                ],
            ],
        )]
        public array $item = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-TS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?IVLTS $validTime = null,
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            nullFlavor: $nullFlavor,
        );
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\DataType;

use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\PostalAddressUse;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\ChoiceGroupItem;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/AD',
    name: 'AD',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class AD extends ANY
{
    /**
     * @param list<PostalAddressUse> $use
     * @param list<ChoiceGroupItem>  $item
     * @param list<IVLTS>            $useablePeriod
     */
    public function __construct(
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@isNotOrdered')]
        public ?bool $isNotOrdered = null,
        #[FhirProperty(
            fhirType: 'code',
            propertyKind: 'enum',
            isArray: true,
            isRequired: false,
            xmlSerializedName: '@use',
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\Enum\PostalAddressUse',
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
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'delimiter',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'country',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'state',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'county',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'city',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'postalCode',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'streetAddressLine',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'houseNumber',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'houseNumberNumeric',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'direction',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'streetName',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'streetNameBase',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'streetNameType',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'additionalLocator',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'unitID',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'unitType',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'careOf',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'censusTract',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'deliveryAddressLine',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'deliveryInstallationType',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'deliveryInstallationArea',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'deliveryInstallationQualifier',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'deliveryMode',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'deliveryModeIdentifier',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'buildingNumberSuffix',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'postBox',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ADXP',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ADXP',
                    'jsonKey'      => 'precinct',
                ],
            ],
        )]
        public array $item = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-TS',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS',
        )]
        public array $useablePeriod = [],
        ?NullFlavor $nullFlavor = null,
    ) {
        parent::__construct(
            nullFlavor: $nullFlavor,
        );
    }
}

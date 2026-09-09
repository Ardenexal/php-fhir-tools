<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\ANY;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\ActClassObservation;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/Criterion',
    name: 'Criterion',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
    propertyOrder: ['nullFlavor', 'realmCode', 'typeId', 'templateId', 'classCode', 'moodCode', 'code', 'text', 'value'],
)]
class Criterion extends InfrastructureRoot
{
    /**
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'enum', isArray: false, isRequired: false, xmlSerializedName: '@classCode')]
        public ?ActClassObservation $classCode = null,
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@moodCode')]
        public string $moodCode = 'EVN.CRT',
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
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/ANY',
            propertyKind: 'polymorphic',
            isArray: false,
            isRequired: false,
            variants: [
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/BL',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\BL',
                    'jsonKey'      => 'value',
                    'typeName'     => 'BL',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ED',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ED',
                    'jsonKey'      => 'value',
                    'typeName'     => 'ED',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/SC',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\SC',
                    'jsonKey'      => 'value',
                    'typeName'     => 'SC',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/ST',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\ST',
                    'jsonKey'      => 'value',
                    'typeName'     => 'ST',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/CV',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\CV',
                    'jsonKey'      => 'value',
                    'typeName'     => 'CV',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/CE',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\CE',
                    'jsonKey'      => 'value',
                    'typeName'     => 'CE',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/CD',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\CD',
                    'jsonKey'      => 'value',
                    'typeName'     => 'CD',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/II',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\II',
                    'jsonKey'      => 'value',
                    'typeName'     => 'II',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/TEL',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\TEL',
                    'jsonKey'      => 'value',
                    'typeName'     => 'TEL',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/AD',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\AD',
                    'jsonKey'      => 'value',
                    'typeName'     => 'AD',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/EN',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\EN',
                    'jsonKey'      => 'value',
                    'typeName'     => 'EN',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/INT',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\INTType',
                    'jsonKey'      => 'value',
                    'typeName'     => 'INT',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/REAL',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\REAL',
                    'jsonKey'      => 'value',
                    'typeName'     => 'REAL',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-PQ',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLPQ',
                    'jsonKey'      => 'value',
                    'typeName'     => 'IVL_PQ',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/PQ',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\PQ',
                    'jsonKey'      => 'value',
                    'typeName'     => 'PQ',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/MO',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\MO',
                    'jsonKey'      => 'value',
                    'typeName'     => 'MO',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/IVL-TS',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\IVLTS',
                    'jsonKey'      => 'value',
                    'typeName'     => 'IVL_TS',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/PIVL-TS',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\PIVLTS',
                    'jsonKey'      => 'value',
                    'typeName'     => 'PIVL_TS',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/EIVL-TS',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\EIVLTS',
                    'jsonKey'      => 'value',
                    'typeName'     => 'EIVL_TS',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/SXPR-TS',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\SXPRTS',
                    'jsonKey'      => 'value',
                    'typeName'     => 'SXPR_TS',
                ],
                [
                    'fhirType'     => 'http://hl7.org/cda/stds/core/StructureDefinition/TS',
                    'propertyKind' => 'complex',
                    'phpType'      => '\Ardenexal\FHIRTools\Component\CdaModels\DataType\TS',
                    'jsonKey'      => 'value',
                    'typeName'     => 'TS',
                ],
            ],
        )]
        public ?ANY $value = null,
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

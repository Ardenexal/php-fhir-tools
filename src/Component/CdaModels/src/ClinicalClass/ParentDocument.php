<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CD;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\ED;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\INTType;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/ParentDocument',
    name: 'ParentDocument',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
class ParentDocument extends InfrastructureRoot
{
    /**
     * @param list<II> $id
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@classCode')]
        public string $classCode = 'DOCCLIN',
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@moodCode')]
        public string $moodCode = 'EVN',
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: true,
            isRequired: true,
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
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?II $setId = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/INT',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?INTType $versionNumber = null,
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

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\CS;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\Enum\NullFlavor;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/Precondition2',
    name: 'Precondition2',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:sdtc',
    propertyOrder: [
        'nullFlavor',
        'realmCode',
        'typeId',
        'templateId',
        'typeCode',
        'negationInd',
        'conjunctionCode',
        'allTrue',
        'allFalse',
        'atLeastOneTrue',
        'atLeastOneFalse',
        'onlyOneTrue',
        'onlyOneFalse',
        'criterion',
    ],
)]
#[FHIRPathInvariant(
    key: 'precondition2-only-one',
    severity: 'error',
    expression: '(allTrue | allFalse | atLeastOneTrue | atLeastOneFalse | onlyOneTrue | onlyOneFalse | criterion).count() = 1',
    human: 'SHALL have only one of allTrue, allFalse, atLeastOneTrue, atLeastOneFalse, onlyOneTrue, onlyOneFalse, or criterion',
)]
class Precondition2 extends InfrastructureRoot
{
    /**
     * @param list<CS> $realmCode
     * @param list<II> $templateId
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@typeCode')]
        public string $typeCode = 'PRCN',
        #[FhirProperty(fhirType: 'boolean', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@negationInd')]
        public ?bool $negationInd = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/CS',
            propertyKind: 'complex',
            isArray: false,
            isRequired: true,
        )]
        public ?CS $conjunctionCode = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PreconditionBase',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PreconditionBase $allTrue = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PreconditionBase',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PreconditionBase $allFalse = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PreconditionBase',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PreconditionBase $atLeastOneTrue = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PreconditionBase',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PreconditionBase $atLeastOneFalse = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PreconditionBase',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PreconditionBase $onlyOneTrue = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/PreconditionBase',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?PreconditionBase $onlyOneFalse = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Criterion',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Criterion $criterion = null,
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

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\CdaModels\DataType\II;
use Ardenexal\FHIRTools\Component\CdaModels\DataType\IdentifiedBy;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/ManufacturedProduct',
    name: 'ManufacturedProduct',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'product-choice',
    severity: 'error',
    expression: '(manufacturedLabeledDrug | manufacturedMaterial).count() = 1',
    human: 'manufacturedLabeledDrug and manufacturedMaterial are mutually exclusive (one must be present)',
)]
class ManufacturedProduct extends InfrastructureRoot
{
    /**
     * @param list<II>           $id
     * @param list<IdentifiedBy> $sdtcIdentifiedBy
     */
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@classCode')]
        public string $classCode = 'MANU',
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/II',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\II',
        )]
        public array $id = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/IdentifiedBy',
            propertyKind: 'complex',
            isArray: true,
            isRequired: false,
            phpType: '\Ardenexal\FHIRTools\Component\CdaModels\DataType\IdentifiedBy',
        )]
        public array $sdtcIdentifiedBy = [],
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/LabeledDrug',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?LabeledDrug $manufacturedLabeledDrug = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Material',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Material $manufacturedMaterial = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/Organization',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?Organization $manufacturerOrganization = null,
    ) {
    }
}

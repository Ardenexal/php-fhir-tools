<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\CdaModels\ClinicalClass;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\LogicalModel;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;

#[LogicalModel(
    url: 'http://hl7.org/cda/stds/core/StructureDefinition/Component',
    name: 'Component',
    fhirVersion: '5.0.0',
    xmlNamespace: 'urn:hl7-org:v3',
)]
#[FHIRPathInvariant(
    key: 'body-choice',
    severity: 'error',
    expression: '(nonXMLBody | structuredBody).count() = 1',
    human: 'Choice of the body required.',
)]
class Component extends InfrastructureRoot
{
    public function __construct(
        #[FhirProperty(fhirType: 'code', propertyKind: 'scalar', isArray: false, isRequired: false, xmlSerializedName: '@typeCode')]
        public string $typeCode = 'COMP',
        #[FhirProperty(
            fhirType: 'boolean',
            propertyKind: 'scalar',
            isArray: false,
            isRequired: false,
            xmlSerializedName: '@contextConductionInd',
        )]
        public bool $contextConductionInd = true,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/NonXMLBody',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?NonXMLBody $nonXMLBody = null,
        #[FhirProperty(
            fhirType: 'http://hl7.org/cda/stds/core/StructureDefinition/StructuredBody',
            propertyKind: 'complex',
            isArray: false,
            isRequired: false,
        )]
        public ?StructuredBody $structuredBody = null,
    ) {
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource\ObservationDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRPathInvariant;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R5\DataType\ObservationDataTypeType;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Some observations have multiple component observations, expressed as separate code value pairs.
 */
#[FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.component', fhirVersion: 'R5')]
#[FHIRPathInvariant(
    key: 'obd-1',
    severity: 'error',
    expression: 'permittedUnit.exists() implies (permittedDataType = \'Quantity\').exists()',
    human: 'If permittedUnit exists, then permittedDataType=Quantity must exist.',
)]
class ObservationDefinitionComponent extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true)]
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Type of observation */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex', isRequired: true), NotBlank]
        public ?CodeableConcept $code = null,
        /** @var array<ObservationDataTypeType> permittedDataType Quantity | CodeableConcept | string | boolean | integer | Range | Ratio | SampledData | time | dateTime | Period */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isArray: true), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/permitted-data-type|5.0.0', strength: 'required')]
        public array $permittedDataType = [],
        /** @var array<Coding> permittedUnit Unit for quantitative results */
        #[FhirProperty(
            fhirType: 'Coding',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\DataType\Coding',
        )]
        #[FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/ucum-units', strength: 'preferred')]
        public array $permittedUnit = [],
        /** @var array<ObservationDefinitionQualifiedValue> qualifiedValue Set of qualified values for observation results */
        #[FhirProperty(
            fhirType: 'unknown',
            propertyKind: 'complex',
            isArray: true,
            phpType: 'Ardenexal\FHIRTools\Component\Models\R5\Resource\ObservationDefinition\ObservationDefinitionQualifiedValue',
        )]
        public array $qualifiedValue = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

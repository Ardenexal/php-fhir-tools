<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ObservationDefinition;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRIsModifier;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\Validation\FHIRValueSetBinding;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;

/**
 * @description Characteristics for quantitative results of this observation.
 */
#[FHIRBackboneElement(parentResource: 'ObservationDefinition', elementPath: 'ObservationDefinition.quantitativeDetails', fhirVersion: 'R4')]
class ObservationDefinitionQuantitativeDetails extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'modifierExtension', isArray: true), FHIRIsModifier(reason: 'Modifier extensions are expected to modify the meaning or interpretation of the element that contains them')]
        public array $modifierExtension = [],
        /** @var CodeableConcept|null customaryUnit Customary unit for quantitative results */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/ucum-units', strength: 'extensible')]
        public ?CodeableConcept $customaryUnit = null,
        /** @var CodeableConcept|null unit SI unit for quantitative results */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex'), FHIRValueSetBinding(valueSetUrl: 'http://hl7.org/fhir/ValueSet/ucum-units', strength: 'extensible')]
        public ?CodeableConcept $unit = null,
        /** @var numeric-string|null conversionFactor SI to Customary unit conversion factor */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $conversionFactor = null,
        /** @var int|null decimalPrecision Decimal precision of observation quantitative results */
        #[FhirProperty(fhirType: 'integer', propertyKind: 'scalar')]
        public ?int $decimalPrecision = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

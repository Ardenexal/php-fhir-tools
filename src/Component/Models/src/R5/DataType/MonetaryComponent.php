<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/MonetaryComponent
 *
 * @description Availability data for an {item}.
 */
#[FHIRComplexType(typeName: 'MonetaryComponent', fhirVersion: 'R5')]
class MonetaryComponent extends DataType
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var PriceComponentTypeType|null type base | surcharge | deduction | discount | tax | informational */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive', isRequired: true), NotBlank]
        public ?PriceComponentTypeType $type = null,
        /** @var CodeableConcept|null code Codes may be used to differentiate between kinds of taxes, surcharges, discounts etc. */
        #[FhirProperty(fhirType: 'CodeableConcept', propertyKind: 'complex')]
        public ?CodeableConcept $code = null,
        /** @var numeric-string|null factor Factor used for calculating this component */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $factor = null,
        /** @var Money|null amount Explicit value amount to be used */
        #[FhirProperty(fhirType: 'Money', propertyKind: 'complex')]
        public ?Money $amount = null,
    ) {
        parent::__construct($id, $extension);
    }
}

<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\Metadata\Attribute\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Metadata\Attribute\FhirProperty;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Money
 *
 * @description An amount of economic utility in some recognized currency.
 */
#[FHIRComplexType(typeName: 'Money', fhirVersion: 'R4')]
class Money extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        #[FhirProperty(fhirType: 'http://hl7.org/fhirpath/System.String', propertyKind: 'scalar', xmlSerializedName: '@id')]
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        #[FhirProperty(fhirType: 'Extension', propertyKind: 'extension', isArray: true)]
        public array $extension = [],
        /** @var numeric-string|null value Numerical value (with implicit precision) */
        #[FhirProperty(fhirType: 'decimal', propertyKind: 'scalar')]
        public ?string $value = null,
        /** @var CurrenciesType|null currency ISO 4217 Currency Code */
        #[FhirProperty(fhirType: 'code', propertyKind: 'primitive')]
        public ?CurrenciesType $currency = null,
    ) {
        parent::__construct($id, $extension);
    }
}

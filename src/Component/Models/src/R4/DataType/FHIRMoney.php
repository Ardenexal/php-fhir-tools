<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Money
 *
 * @description An amount of economic utility in some recognized currency.
 */
#[FHIRComplexType(typeName: 'Money', fhirVersion: 'R4')]
class FHIRMoney extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRDecimal|null value Numerical value (with implicit precision) */
        public ?\FHIRDecimal $value = null,
        /** @var FHIRCurrenciesType|null currency ISO 4217 Currency Code */
        public ?\FHIRCurrenciesType $currency = null,
    ) {
        parent::__construct($id, $extension);
    }
}

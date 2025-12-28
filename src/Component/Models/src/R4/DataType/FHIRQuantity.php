<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Quantity
 *
 * @description A measured amount (or an amount that can potentially be measured). Note that measured amounts include amounts that are not precisely quantified, including amounts involving arbitrary units and floating currencies.
 */
#[FHIRComplexType(typeName: 'Quantity', fhirVersion: 'R4')]
class FHIRQuantity extends FHIRElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRDecimal|null value Numerical value (with implicit precision) */
        public ?\FHIRDecimal $value = null,
        /** @var FHIRQuantityComparatorType|null comparator < | <= | >= | > - how to understand the value */
        public ?\FHIRQuantityComparatorType $comparator = null,
        /** @var FHIRString|string|null unit Unit representation */
        public \FHIRString|string|null $unit = null,
        /** @var FHIRUri|null system System that defines coded unit form */
        public ?\FHIRUri $system = null,
        /** @var FHIRCode|null code Coded form of the unit */
        public ?\FHIRCode $code = null,
    ) {
        parent::__construct($id, $extension);
    }
}

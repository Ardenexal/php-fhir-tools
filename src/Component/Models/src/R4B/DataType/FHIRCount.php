<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRCode;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRDecimal;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRString;
use Ardenexal\FHIRTools\Component\Models\R4B\Primitive\FHIRUri;
use Ardenexal\FHIRTools\Component\Models\R4B\Resource\FHIRQuantityComparatorType;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Count
 *
 * @description A measured amount (or an amount that can potentially be measured). Note that measured amounts include amounts that are not precisely quantified, including amounts involving arbitrary units and floating currencies.
 */
#[FHIRComplexType(typeName: 'Count', fhirVersion: 'R4B')]
class FHIRCount extends FHIRQuantity
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var FHIRDecimal|null value Numerical value (with implicit precision) */
        public ?FHIRDecimal $value = null,
        /** @var FHIRQuantityComparatorType|null comparator < | <= | >= | > - how to understand the value */
        public ?FHIRQuantityComparatorType $comparator = null,
        /** @var FHIRString|string|null unit Unit representation */
        public FHIRString|string|null $unit = null,
        /** @var FHIRUri|null system System that defines coded unit form */
        public ?FHIRUri $system = null,
        /** @var FHIRCode|null code Coded form of the unit */
        public ?FHIRCode $code = null,
    ) {
        parent::__construct($id, $extension, $value, $comparator, $unit, $system, $code);
    }
}

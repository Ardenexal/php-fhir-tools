<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRComplexType;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\CodePrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\UriPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/Quantity
 *
 * @description A measured amount (or an amount that can potentially be measured). Note that measured amounts include amounts that are not precisely quantified, including amounts involving arbitrary units and floating currencies.
 */
#[FHIRComplexType(typeName: 'Quantity', fhirVersion: 'R4')]
class Quantity extends Element
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var float|null value Numerical value (with implicit precision) */
        public ?float $value = null,
        /** @var QuantityComparatorType|null comparator < | <= | >= | > - how to understand the value */
        public ?QuantityComparatorType $comparator = null,
        /** @var StringPrimitive|string|null unit Unit representation */
        public StringPrimitive|string|null $unit = null,
        /** @var UriPrimitive|null system System that defines coded unit form */
        public ?UriPrimitive $system = null,
        /** @var CodePrimitive|null code Coded form of the unit */
        public ?CodePrimitive $code = null,
    ) {
        parent::__construct($id, $extension);
    }
}

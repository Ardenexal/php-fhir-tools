<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\DataType;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @author HL7 FHIR Standard
 *
 * @see http://hl7.org/fhir/StructureDefinition/SubstanceAmount
 *
 * @description Chemical substances are a single substance type whose primary defining element is the molecular structure. Chemical substances shall be defined on the basis of their complete covalent molecular structure; the presence of a salt (counter-ion) and/or solvates (water, alcohols) is also captured. Purity, grade, physical form or particle size are not taken into account in the definition of a chemical substance or in the assignment of a Substance ID.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceAmount', elementPath: 'SubstanceAmount', fhirVersion: 'R4')]
class SubstanceAmount extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Quantity|Range|StringPrimitive|string|null amountX Used to capture quantitative values for a variety of elements. If only limits are given, the arithmetic mean would be the average. If only a single definite value for a given element is given, it would be captured in this field */
        public Quantity|Range|StringPrimitive|string|null $amountX = null,
        /** @var CodeableConcept|null amountType Most elements that require a quantitative value will also have a field called amount type. Amount type should always be specified because the actual value of the amount is often dependent on it. EXAMPLE: In capturing the actual relative amounts of substances or molecular fragments it is essential to indicate whether the amount refers to a mole ratio or weight ratio. For any given element an effort should be made to use same the amount type for all related definitional elements */
        public ?CodeableConcept $amountType = null,
        /** @var StringPrimitive|string|null amountText A textual comment on a numeric value */
        public StringPrimitive|string|null $amountText = null,
        /** @var SubstanceAmountReferenceRange|null referenceRange Reference range of possible or expected values */
        public ?SubstanceAmountReferenceRange $referenceRange = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

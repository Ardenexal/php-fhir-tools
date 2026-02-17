<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description General specifications for this substance, including how it is related to other substances.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.property', fhirVersion: 'R4')]
class SubstanceSpecificationProperty extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null category A category for this property, e.g. Physical, Chemical, Enzymatic */
        public ?CodeableConcept $category = null,
        /** @var CodeableConcept|null code Property type e.g. viscosity, pH, isoelectric point */
        public ?CodeableConcept $code = null,
        /** @var StringPrimitive|string|null parameters Parameters that were used in the measurement of a property (e.g. for viscosity: measured at 20C with a pH of 7.1) */
        public StringPrimitive|string|null $parameters = null,
        /** @var Reference|CodeableConcept|null definingSubstanceX A substance upon which a defining property depends (e.g. for solubility: in water, in alcohol) */
        public Reference|CodeableConcept|null $definingSubstanceX = null,
        /** @var Quantity|StringPrimitive|string|null amountX Quantitative value for this property */
        public Quantity|StringPrimitive|string|null $amountX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

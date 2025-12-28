<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRCodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRExtension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRQuantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRReference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\FHIRString;

/**
 * @description General specifications for this substance, including how it is related to other substances.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.property', fhirVersion: 'R4')]
class FHIRSubstanceSpecificationProperty extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRCodeableConcept|null category A category for this property, e.g. Physical, Chemical, Enzymatic */
        public ?FHIRCodeableConcept $category = null,
        /** @var FHIRCodeableConcept|null code Property type e.g. viscosity, pH, isoelectric point */
        public ?FHIRCodeableConcept $code = null,
        /** @var FHIRString|string|null parameters Parameters that were used in the measurement of a property (e.g. for viscosity: measured at 20C with a pH of 7.1) */
        public FHIRString|string|null $parameters = null,
        /** @var FHIRReference|FHIRCodeableConcept|null definingSubstanceX A substance upon which a defining property depends (e.g. for solubility: in water, in alcohol) */
        public FHIRReference|FHIRCodeableConcept|null $definingSubstanceX = null,
        /** @var FHIRQuantity|FHIRString|string|null amountX Quantitative value for this property */
        public FHIRQuantity|FHIRString|string|null $amountX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

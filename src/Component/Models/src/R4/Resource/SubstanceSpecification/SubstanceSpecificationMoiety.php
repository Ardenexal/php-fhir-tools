<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceSpecification;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Moiety, for structural modifications.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceSpecification', elementPath: 'SubstanceSpecification.moiety', fhirVersion: 'R4')]
class SubstanceSpecificationMoiety extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null role Role that the moiety is playing */
        public ?CodeableConcept $role = null,
        /** @var Identifier|null identifier Identifier by which this moiety substance is known */
        public ?Identifier $identifier = null,
        /** @var StringPrimitive|string|null name Textual name for this moiety substance */
        public StringPrimitive|string|null $name = null,
        /** @var CodeableConcept|null stereochemistry Stereochemistry type */
        public ?CodeableConcept $stereochemistry = null,
        /** @var CodeableConcept|null opticalActivity Optical activity type */
        public ?CodeableConcept $opticalActivity = null,
        /** @var StringPrimitive|string|null molecularFormula Molecular formula */
        public StringPrimitive|string|null $molecularFormula = null,
        /** @var Quantity|StringPrimitive|string|null amountX Quantitative value for this moiety */
        public Quantity|StringPrimitive|string|null $amountX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

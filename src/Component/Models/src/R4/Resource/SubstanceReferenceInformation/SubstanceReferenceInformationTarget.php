<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SubstanceReferenceInformation;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceReferenceInformation', elementPath: 'SubstanceReferenceInformation.target', fhirVersion: 'R4')]
class SubstanceReferenceInformationTarget extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Identifier|null target Todo */
        public ?Identifier $target = null,
        /** @var CodeableConcept|null type Todo */
        public ?CodeableConcept $type = null,
        /** @var CodeableConcept|null interaction Todo */
        public ?CodeableConcept $interaction = null,
        /** @var CodeableConcept|null organism Todo */
        public ?CodeableConcept $organism = null,
        /** @var CodeableConcept|null organismType Todo */
        public ?CodeableConcept $organismType = null,
        /** @var Quantity|Range|StringPrimitive|string|null amountX Todo */
        public Quantity|Range|StringPrimitive|string|null $amountX = null,
        /** @var CodeableConcept|null amountType Todo */
        public ?CodeableConcept $amountType = null,
        /** @var array<Reference> source Todo */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

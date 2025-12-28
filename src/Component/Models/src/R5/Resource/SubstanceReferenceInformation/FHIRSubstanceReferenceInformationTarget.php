<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Todo.
 */
#[FHIRBackboneElement(parentResource: 'SubstanceReferenceInformation', elementPath: 'SubstanceReferenceInformation.target', fhirVersion: 'R5')]
class FHIRSubstanceReferenceInformationTarget extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null target Todo */
        public ?\FHIRIdentifier $target = null,
        /** @var FHIRCodeableConcept|null type Todo */
        public ?\FHIRCodeableConcept $type = null,
        /** @var FHIRCodeableConcept|null interaction Todo */
        public ?\FHIRCodeableConcept $interaction = null,
        /** @var FHIRCodeableConcept|null organism Todo */
        public ?\FHIRCodeableConcept $organism = null,
        /** @var FHIRCodeableConcept|null organismType Todo */
        public ?\FHIRCodeableConcept $organismType = null,
        /** @var FHIRQuantity|FHIRRange|FHIRString|string|null amountX Todo */
        public \FHIRQuantity|\FHIRRange|\FHIRString|string|null $amountX = null,
        /** @var FHIRCodeableConcept|null amountType Todo */
        public ?\FHIRCodeableConcept $amountType = null,
        /** @var array<FHIRReference> source Todo */
        public array $source = [],
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

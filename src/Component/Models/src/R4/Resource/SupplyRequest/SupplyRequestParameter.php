<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\SupplyRequest;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Range;

/**
 * @description Specific parameters for the ordered item.  For example, the size of the indicated item.
 */
#[FHIRBackboneElement(parentResource: 'SupplyRequest', elementPath: 'SupplyRequest.parameter', fhirVersion: 'R4')]
class SupplyRequestParameter extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null code Item detail */
        public ?CodeableConcept $code = null,
        /** @var CodeableConcept|Quantity|Range|bool|null valueX Value of detail */
        public CodeableConcept|Quantity|Range|bool|null $valueX = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

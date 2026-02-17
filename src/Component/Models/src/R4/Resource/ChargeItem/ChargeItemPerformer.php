<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\ChargeItem;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @description Indicates who or what performed or participated in the charged service.
 */
#[FHIRBackboneElement(parentResource: 'ChargeItem', elementPath: 'ChargeItem.performer', fhirVersion: 'R4')]
class ChargeItemPerformer extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var CodeableConcept|null function What type of performance was done */
        public ?CodeableConcept $function = null,
        /** @var Reference|null actor Individual who was performing */
        #[NotBlank]
        public ?Reference $actor = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

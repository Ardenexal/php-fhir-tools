<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Immunization;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;

/**
 * @description Categorical data indicating that an adverse event is associated in time to an immunization.
 */
#[FHIRBackboneElement(parentResource: 'Immunization', elementPath: 'Immunization.reaction', fhirVersion: 'R4')]
class ImmunizationReaction extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var DateTimePrimitive|null date When reaction started */
        public ?DateTimePrimitive $date = null,
        /** @var Reference|null detail Additional information on reaction */
        public ?Reference $detail = null,
        /** @var bool|null reported Indicates self-reported reaction */
        public ?bool $reported = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

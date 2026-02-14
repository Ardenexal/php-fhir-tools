<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Substance;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Identifier;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Quantity;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\DateTimePrimitive;

/**
 * @description Substance may be used to describe a kind of substance, or a specific package/container of the substance: an instance.
 */
#[FHIRBackboneElement(parentResource: 'Substance', elementPath: 'Substance.instance', fhirVersion: 'R4')]
class SubstanceInstance extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Identifier|null identifier Identifier of the package/container */
        public ?Identifier $identifier = null,
        /** @var DateTimePrimitive|null expiry When no longer valid to use */
        public ?DateTimePrimitive $expiry = null,
        /** @var Quantity|null quantity Amount of substance in the package */
        public ?Quantity $quantity = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

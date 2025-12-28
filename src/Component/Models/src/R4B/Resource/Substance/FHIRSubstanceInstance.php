<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4B\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Substance may be used to describe a kind of substance, or a specific package/container of the substance: an instance.
 */
#[FHIRBackboneElement(parentResource: 'Substance', elementPath: 'Substance.instance', fhirVersion: 'R4B')]
class FHIRSubstanceInstance extends \Ardenexal\FHIRTools\Component\Models\R4B\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRIdentifier|null identifier Identifier of the package/container */
        public ?\FHIRIdentifier $identifier = null,
        /** @var FHIRDateTime|null expiry When no longer valid to use */
        public ?\FHIRDateTime $expiry = null,
        /** @var FHIRQuantity|null quantity Amount of substance in the package */
        public ?\FHIRQuantity $quantity = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

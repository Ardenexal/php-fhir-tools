<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource\Contract;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\BackboneElement;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\CodeableConcept;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Extension;
use Ardenexal\FHIRTools\Component\Models\R4\DataType\Reference;
use Ardenexal\FHIRTools\Component\Models\R4\Primitive\StringPrimitive;

/**
 * @description Circumstance of the asset.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.asset.context', fhirVersion: 'R4')]
class ContractTermAssetContext extends BackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<Extension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<Extension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var Reference|null reference Creator,custodian or owner */
        public ?Reference $reference = null,
        /** @var array<CodeableConcept> code Codeable asset context */
        public array $code = [],
        /** @var StringPrimitive|string|null text Context description */
        public StringPrimitive|string|null $text = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

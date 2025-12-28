<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R4\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Circumstance of the asset.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.asset.context', fhirVersion: 'R4')]
class FHIRContractTermAssetContext extends \Ardenexal\FHIRTools\Component\Models\R4\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var FHIRReference|null reference Creator,custodian or owner */
        public ?\FHIRReference $reference = null,
        /** @var array<FHIRCodeableConcept> code Codeable asset context */
        public array $code = [],
        /** @var FHIRString|string|null text Context description */
        public \FHIRString|string|null $text = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}

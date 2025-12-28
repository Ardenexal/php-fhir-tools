<?php

declare(strict_types=1);

namespace Ardenexal\FHIRTools\Component\Models\R5\Resource;

use Ardenexal\FHIRTools\Component\CodeGeneration\Attributes\FHIRBackboneElement;

/**
 * @description Entity of the action.
 */
#[FHIRBackboneElement(parentResource: 'Contract', elementPath: 'Contract.term.action.subject', fhirVersion: 'R5')]
class FHIRContractTermActionSubject extends \Ardenexal\FHIRTools\Component\Models\R5\DataType\FHIRBackboneElement
{
    public function __construct(
        /** @var string|null id Unique id for inter-element referencing */
        public ?string $id = null,
        /** @var array<FHIRExtension> extension Additional content defined by implementations */
        public array $extension = [],
        /** @var array<FHIRExtension> modifierExtension Extensions that cannot be ignored even if unrecognized */
        public array $modifierExtension = [],
        /** @var array<FHIRReference> reference Entity of the action */
        public array $reference = [],
        /** @var FHIRCodeableConcept|null role Role type of the agent */
        public ?\FHIRCodeableConcept $role = null,
    ) {
        parent::__construct($id, $extension, $modifierExtension);
    }
}
